<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])
            ->latest();

        // Фильтрация по статусу
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Поиск по номеру заказа, email или имени
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20);

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
            'filters' => $request->only(['search', 'status']),
            'statuses' => [
                'pending' => 'Ожидание',
                'processing' => 'В обработке',
                'completed' => 'Завершен',
                'cancelled' => 'Отменен',
                'shipped' => 'Отправлен',
            ]
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.recipe']);

        return Inertia::render('Admin/Orders/Show', [
            'order' => $order,
            'statuses' => [
                'pending' => 'Ожидание',
                'processing' => 'В обработке',
                'completed' => 'Завершен',
                'cancelled' => 'Отменен',
                'shipped' => 'Отправлен',
            ]
        ]);
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,shipped'
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $order->status;
            $newStatus = $request->status;

            // Если заказ отменяется - возвращаем товары на склад
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->recipe) {
                        $item->recipe->increment('quantity', $item->quantity);
                    }
                }
                $order->cancelled_at = now();
            }

            // Если заказ был отменен, но теперь активируется - снова уменьшаем количество
            if ($oldStatus === 'cancelled' && !in_array($newStatus, ['cancelled'])) {
                foreach ($order->items as $item) {
                    if ($item->recipe && $item->recipe->quantity >= $item->quantity) {
                        $item->recipe->decrement('quantity', $item->quantity);
                    } else {
                        throw new \Exception("Недостаточно товара: {$item->name}");
                    }
                }
                $order->cancelled_at = null;
            }

            $order->status = $newStatus;
            $order->save();

            DB::commit();

            return back()->with('success', 'Статус заказа успешно обновлен');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Order status update failed: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Произошла ошибка при обновлении статуса: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update order details.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'postal_code' => 'nullable|string|max:20',
            'comment' => 'nullable|string|max:1000',
            'delivery_price' => 'required|numeric|min:0',
        ]);

        try {
            $order->update($validated);

            // Пересчитываем итоговую сумму
            $order->total = $order->subtotal + $order->delivery_price;
            $order->save();

            return back()->with('success', 'Данные заказа успешно обновлены');

        } catch (\Exception $e) {
            \Log::error('Order update failed: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Произошла ошибка при обновлении заказа'
            ]);
        }
    }

    /**
     * Cancel order (admin version).
     */
    public function cancel(Order $order)
    {
        try {
            DB::beginTransaction();

            // Возвращаем товары на склад
            foreach ($order->items as $item) {
                if ($item->recipe) {
                    $item->recipe->increment('quantity', $item->quantity);
                }
            }

            // Обновляем статус заказа
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);

            DB::commit();

            return back()->with('success', 'Заказ успешно отменен');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Admin order cancellation failed: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Произошла ошибка при отмене заказа'
            ]);
        }
    }

    /**
     * Delete order.
     */
    public function destroy(Order $order)
    {
        try {
            // Можно удалять только отмененные заказы
            if ($order->status !== 'cancelled') {
                return back()->withErrors([
                    'error' => 'Можно удалять только отмененные заказы'
                ]);
            }

            $order->delete();

            return redirect()->route('admin.orders.index')
                ->with('success', 'Заказ успешно удален');

        } catch (\Exception $e) {
            \Log::error('Order deletion failed: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Произошла ошибка при удалении заказа'
            ]);
        }
    }

    /**
     * Get orders statistics.
     */
    public function statistics()
    {
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'today' => Order::whereDate('created_at', today())->count(),
            'revenue' => Order::where('status', 'completed')->sum('total'),
        ];

        return response()->json($stats);
    }
}