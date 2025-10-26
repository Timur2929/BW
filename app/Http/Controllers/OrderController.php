<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function success($orderNumber)
{
    $order = Order::where('order_number', $orderNumber)
        ->with('items')
        ->firstOrFail();
    
    return Inertia::render('Orders/Success', [
        'order' => $order,
    ]);
}
   public function store(Request $request)
{
    // Валидация данных
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'city' => 'required|string|max:255',
        'address' => 'required|string|max:500',
        'postal_code' => 'nullable|string|max:20',
        'comment' => 'nullable|string|max:1000',
        'items' => 'required|array|min:1',
        'subtotal' => 'required|numeric|min:0',
        'delivery_price' => 'required|numeric|min:0',
        'total' => 'required|numeric|min:0',
        'total_items' => 'required|integer|min:1',
    ]);

    try {
        DB::beginTransaction();

        // Создаем заказ
        $order = Order::create([
            'user_id' => auth()->id(),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'address' => $validated['address'],
            'postal_code' => $validated['postal_code'] ?? null,
            'comment' => $validated['comment'] ?? null,
            'subtotal' => $validated['subtotal'],
            'delivery_price' => $validated['delivery_price'],
            'total' => $validated['total'],
            'total_items' => $validated['total_items'],
            'status' => 'pending',
        ]);

        // Создаем элементы заказа и уменьшаем количество
        foreach ($validated['items'] as $item) {
            // Находим рецепт
            $recipe = \App\Models\Recipe::where('name', $item['name'])->first();
            
            if (!$recipe) {
                // Если рецепт не найден, создаем временный
                $recipe = \App\Models\Recipe::create([
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => 10,
                    'cooking_time' => '1-2 дня',
                    'status' => 'approved',
                    'description' => 'Временный товар'
                ]);
            }

            // УМЕНЬШАЕМ КОЛИЧЕСТВО ТОВАРА
            if ($recipe->quantity >= $item['cartQuantity']) {
                $recipe->decrement('quantity', $item['cartQuantity']);
            } else {
                throw new \Exception("Недостаточно товара: {$recipe->name}");
            }

            OrderItem::create([
                'order_id' => $order->id,
                'recipe_id' => $recipe->id,
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['cartQuantity'],
                'image' => $item['image'] ?? null,
            ]);
        }

        DB::commit();

        return redirect()->route('orders.success', $order->order_number);

    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Order creation failed: ' . $e->getMessage());
        
        return back()->withErrors([
            'error' => 'Произошла ошибка при оформлении заказа: ' . $e->getMessage()
        ]);
    }
}
}