<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Recipe::with(['user', 'category'])
            ->latest();

        // Фильтрация по статусу
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Поиск
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $recipes = $query->paginate(15);

        return Inertia::render('Admin/Recipes/Index', [
            'recipes' => $recipes,
            'filters' => [
                'search' => $request->search,
                'status' => $request->status
            ],
            'statuses' => [
                'all' => 'Все',
                Recipe::STATUS_PENDING => 'На модерации',
                Recipe::STATUS_APPROVED => 'Одобрено',
                Recipe::STATUS_REJECTED => 'Отклонено',
                Recipe::STATUS_REVISION => 'На доработке'
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        
        return Inertia::render('Admin/Recipes/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'cooking_time' => 'required|string',
            'servings' => 'nullable|integer|min:1',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.amount' => 'required|string|max:50',
            'ingredients.*.unit' => 'required|string|max:50',
            'steps' => 'required|array|min:1',
            'steps.*.description' => 'required|string',
            'steps.*.order' => 'required|integer|min:1',
            'step_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            \DB::beginTransaction();

            // Создаем рецепт
            $recipe = new Recipe();
            $recipe->name = $validated['name'];
            $recipe->description = $validated['description'];
            $recipe->category_id = $validated['category_id'];
            $recipe->cooking_time = $validated['cooking_time'];
            $recipe->servings = $validated['servings'] ?? 1;
            $recipe->difficulty = $validated['difficulty'] ?? 'medium';
            $recipe->price = $validated['price'];
            $recipe->quantity = $validated['quantity'];
            $recipe->user_id = auth()->id();
            $recipe->status = Recipe::STATUS_APPROVED; // Админ создает сразу одобренный
            $recipe->approved_at = now();
            
            // Сохраняем основное изображение
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('recipe-images', 'public');
                $recipe->image = $path;
            }
            
            $recipe->save();

            // Сохраняем ингредиенты
            foreach ($validated['ingredients'] as $ingredientData) {
                $recipe->ingredients()->create([
                    'name' => $ingredientData['name'],
                    'amount' => $ingredientData['amount'],
                    'unit' => $ingredientData['unit']
                ]);
            }

            // Сохраняем шаги
            foreach ($validated['steps'] as $index => $stepData) {
                $step = $recipe->steps()->create([
                    'description' => $stepData['description'],
                    'order' => $stepData['order']
                ]);

                if ($request->hasFile("step_images.{$index}")) {
                    $path = $request->file("step_images.{$index}")->store('recipe-steps', 'public');
                    $step->image = $path;
                    $step->save();
                }
            }

            \DB::commit();

            return redirect()->route('admin.recipes.index')
                ->with('success', 'Рецепт успешно создан!');

        } catch (\Exception $e) {
            \DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ошибка при создании рецепта: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        $recipe->load(['ingredients', 'steps', 'category']);
        
        $categories = Category::all();
        
        return Inertia::render('Admin/Recipes/Edit', [
            'recipe' => $recipe,
            'categories' => $categories,
            'statuses' => [
                Recipe::STATUS_PENDING => 'На модерации',
                Recipe::STATUS_APPROVED => 'Одобрено',
                Recipe::STATUS_REJECTED => 'Отклонено',
                Recipe::STATUS_REVISION => 'На доработке'
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'cooking_time' => 'required|string',
            'servings' => 'nullable|integer|min:1',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:pending,approved,rejected,revision',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.amount' => 'required|string|max:50',
            'ingredients.*.unit' => 'required|string|max:50',
            'steps' => 'required|array|min:1',
            'steps.*.description' => 'required|string',
            'steps.*.order' => 'required|integer|min:1',
            'step_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            \DB::beginTransaction();

            // Обновляем рецепт
            $recipe->name = $validated['name'];
            $recipe->description = $validated['description'];
            $recipe->category_id = $validated['category_id'];
            $recipe->cooking_time = $validated['cooking_time'];
            $recipe->servings = $validated['servings'] ?? 1;
            $recipe->difficulty = $validated['difficulty'] ?? 'medium';
            $recipe->price = $validated['price'];
            $recipe->quantity = $validated['quantity'];
            $recipe->status = $validated['status'];
            
            // Обновляем изображение если есть новое
            if ($request->hasFile('image')) {
                // Удаляем старое изображение
                if ($recipe->image) {
                    Storage::disk('public')->delete($recipe->image);
                }
                $path = $request->file('image')->store('recipe-images', 'public');
                $recipe->image = $path;
            }
            
            $recipe->save();

            // Обновляем ингредиенты (удаляем старые, создаем новые)
            $recipe->ingredients()->delete();
            foreach ($validated['ingredients'] as $ingredientData) {
                $recipe->ingredients()->create([
                    'name' => $ingredientData['name'],
                    'amount' => $ingredientData['amount'],
                    'unit' => $ingredientData['unit']
                ]);
            }

            // Обновляем шаги
            $recipe->steps()->delete();
            foreach ($validated['steps'] as $index => $stepData) {
                $step = $recipe->steps()->create([
                    'description' => $stepData['description'],
                    'order' => $stepData['order']
                ]);

                if ($request->hasFile("step_images.{$index}")) {
                    $path = $request->file("step_images.{$index}")->store('recipe-steps', 'public');
                    $step->image = $path;
                    $step->save();
                }
            }

            \DB::commit();

            return redirect()->route('admin.recipes.index')
                ->with('success', 'Рецепт успешно обновлен!');

        } catch (\Exception $e) {
            \DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ошибка при обновлении рецепта: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        try {
            // Удаляем связанные изображения
            if ($recipe->image) {
                Storage::disk('public')->delete($recipe->image);
            }
            
            // Удаляем изображения шагов
            foreach ($recipe->steps as $step) {
                if ($step->image) {
                    Storage::disk('public')->delete($step->image);
                }
            }
            
            $recipe->delete();

            return redirect()->route('admin.recipes.index')
                ->with('success', 'Рецепт успешно удален!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ошибка при удалении рецепта: ' . $e->getMessage());
        }
    }

    /**
     * Display pending recipes for moderation
     */
    public function pending()
    {
        $recipes = Recipe::where('status', Recipe::STATUS_PENDING)
            ->with(['user', 'category'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/Recipes/Pending', [
            'recipes' => $recipes
        ]);
    }

    /**
     * Approve recipe
     */
    public function approve(Recipe $recipe)
    {
        $recipe->update([
            'status' => Recipe::STATUS_APPROVED,
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Рецепт одобрен');
    }

    /**
     * Reject recipe
     */
    public function reject(Recipe $recipe, Request $request)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|min:10'
        ]);

        $recipe->update([
            'status' => Recipe::STATUS_REJECTED,
            'rejected_at' => now(),
            'rejection_reason' => $validated['rejection_reason']
        ]);

        return redirect()->back()->with('success', 'Рецепт отклонен');
    }

    /**
     * Send recipe to revision
     */
    public function sendToRevision(Recipe $recipe, Request $request)
    {
        $validated = $request->validate([
            'revision_comment' => 'required|min:10'
        ]);

        $recipe->update([
            'status' => Recipe::STATUS_REVISION,
            'revision_comment' => $validated['revision_comment']
        ]);

        return redirect()->back()->with('success', 'Рецепт отправлен на доработку');
    }
}