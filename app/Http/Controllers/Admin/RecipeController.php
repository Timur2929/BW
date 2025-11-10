<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $query = Recipe::with(['user', 'category', 'ingredients'])
        ->latest();

    // Поиск
    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Фильтр по статусу
    if ($request->has('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }

    $recipes = $query->paginate(12);

    // Преобразуем данные рецептов для фронтенда
    $recipes->getCollection()->transform(function ($recipe) {
        return [
            'id' => $recipe->id,
            'name' => $recipe->name,
            'description' => $recipe->description,
            'cooking_time' => $recipe->cooking_time,
            'image' => $recipe->image
                ? (str_starts_with($recipe->image, '/')
                    ? asset($recipe->image)
                    : Storage::url($recipe->image))
                : asset('/images/placeholder.png'),
            'price' => $recipe->price,
            'quantity' => $recipe->quantity,
            'status' => $recipe->status,
            'rating' => $recipe->rating,
            'views' => $recipe->views,
            'created_at' => $recipe->created_at,
            'user' => [
                'id' => $recipe->user->id,
                'full_name' => $recipe->user->full_name,
                'avatar' => $recipe->user->avatar
                    ? (str_starts_with($recipe->user->avatar, '/')
                        ? asset($recipe->user->avatar)
                        : Storage::url($recipe->user->avatar))
                    : asset('/images/profile/default-avatar.png'),
            ],
            'category' => $recipe->category ? [
                'id' => $recipe->category->id,
                'name' => $recipe->category->name
            ] : null
        ];
    });

    return Inertia::render('Admin/Recipes/Index', [
        'recipes' => $recipes,
        'filters' => [
            'search' => $request->search,
            'status' => $request->status
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
        ]);

        try {
            \DB::beginTransaction();

            // Создаем украшение
            $recipe = new Recipe();
            $recipe->name = $validated['name'];
            $recipe->description = $validated['description'];
            $recipe->category_id = $validated['category_id'];
            $recipe->cooking_time = $validated['cooking_time'];
            $recipe->price = $validated['price'];
            $recipe->quantity = $validated['quantity'];
            $recipe->user_id = auth()->id();
            $recipe->status = Recipe::STATUS_APPROVED; // Админ сразу публикует
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
            foreach ($validated['steps'] as $stepData) {
                $recipe->steps()->create([
                    'description' => $stepData['description'],
                    'order' => $stepData['order']
                ]);
            }

            \DB::commit();

            return redirect()->route('admin.recipes.index')
                ->with('success', 'Украшение успешно создано!');

        } catch (\Exception $e) {
            \DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Произошла ошибка при создании украшения: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        $recipe->load(['user', 'category', 'ingredients', 'steps']);

        return Inertia::render('Admin/Recipes/Show', [
            'recipe' => $this->formatRecipeData($recipe)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        $recipe->load(['ingredients', 'steps']);
        $categories = Category::all();

        return Inertia::render('Admin/Recipes/Edit', [
            'recipe' => $this->formatRecipeData($recipe),
            'categories' => $categories
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
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.amount' => 'required|string|max:50',
            'ingredients.*.unit' => 'required|string|max:50',
            'steps' => 'required|array|min:1',
            'steps.*.description' => 'required|string',
            'steps.*.order' => 'required|integer|min:1',
        ]);

        try {
            \DB::beginTransaction();

            // Обновляем украшение
            $recipe->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'cooking_time' => $validated['cooking_time'],
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
            ]);
            
            // Обновляем изображение, если есть
            if ($request->hasFile('image')) {
                // Удаляем старое изображение
                if ($recipe->image) {
                    Storage::disk('public')->delete($recipe->image);
                }
                
                $path = $request->file('image')->store('recipe-images', 'public');
                $recipe->image = $path;
                $recipe->save();
            }

            // Обновляем ингредиенты
            $recipe->ingredients()->delete();
            foreach ($validated['ingredients'] as $ingredientData) {
                $recipe->ingredients()->create($ingredientData);
            }

            // Обновляем шаги
            $recipe->steps()->delete();
            foreach ($validated['steps'] as $stepData) {
                $recipe->steps()->create($stepData);
            }

            \DB::commit();

            return redirect()->route('admin.recipes.index')
                ->with('success', 'Украшение успешно обновлено!');

        } catch (\Exception $e) {
            \DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Произошла ошибка при обновлении украшения: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        try {
            // Удаляем связанные данные
            $recipe->ingredients()->delete();
            $recipe->steps()->delete();
            
            // Удаляем изображения
            if ($recipe->image) {
                Storage::disk('public')->delete($recipe->image);
            }
            
            // Удаляем само украшение
            $recipe->delete();

            return redirect()->route('admin.recipes.index')
                ->with('success', 'Украшение успешно удалено!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Произошла ошибка при удалении украшения: ' . $e->getMessage());
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
     * Update recipe status
     */
    public function updateStatus(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,revision',
            'rejection_reason' => 'required_if:status,rejected|string|min:10',
            'revision_comment' => 'required_if:status,revision|string|min:10',
        ]);

        try {
            $updateData = ['status' => $validated['status']];

            if ($validated['status'] === Recipe::STATUS_APPROVED) {
                $updateData['approved_at'] = now();
                $updateData['rejection_reason'] = null;
                $updateData['revision_comment'] = null;
            } elseif ($validated['status'] === Recipe::STATUS_REJECTED) {
                $updateData['rejected_at'] = now();
                $updateData['rejection_reason'] = $validated['rejection_reason'];
                $updateData['revision_comment'] = null;
            } elseif ($validated['status'] === Recipe::STATUS_REVISION) {
                $updateData['revision_comment'] = $validated['revision_comment'];
                $updateData['rejection_reason'] = null;
            }

            $recipe->update($updateData);

            return redirect()->back()
                ->with('success', 'Статус украшения успешно обновлен!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Произошла ошибка при обновлении статуса: ' . $e->getMessage());
        }
    }

    /**
     * Format recipe data for frontend
     */
    private function formatRecipeData(Recipe $recipe)
    {
        return [
            'id' => $recipe->id,
            'name' => $recipe->name,
            'description' => $recipe->description,
            'cooking_time' => $recipe->cooking_time,
            'image' => $recipe->image ? Storage::url($recipe->image) : null,
            'price' => $recipe->price,
            'quantity' => $recipe->quantity,
            'status' => $recipe->status,
            'rating' => $recipe->rating,
            'views' => $recipe->views,
            'created_at' => $recipe->created_at,
            'user' => [
                'id' => $recipe->user->id,
                'full_name' => $recipe->user->full_name,
            ],
            'category' => $recipe->category ? [
                'id' => $recipe->category->id,
                'name' => $recipe->category->name
            ] : null,
            'ingredients' => $recipe->ingredients->map(function ($ingredient) {
                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'amount' => $ingredient->amount,
                    'unit' => $ingredient->unit
                ];
            }),
            'steps' => $recipe->steps->map(function ($step) {
                return [
                    'id' => $step->id,
                    'description' => $step->description,
                    'order' => $step->order,
                    'image' => $step->image ? Storage::url($step->image) : null
                ];
            })->sortBy('order')->values(),
        ];
    }
}