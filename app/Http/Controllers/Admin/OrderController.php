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
        $query = Order::with(['user', 'items.recipe'])
            ->orderBy('created_at', 'desc');

        // Фильтрация по статусу
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Поиск по номеру заказа
        if ($request->has('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->paginate(15);

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
            'filters' => [
                'status' => $request->status ?? 'all',
                'search' => $request->search ?? '',
            ],
            'statusOptions' => [
                'all' => 'Все заказы',
                'pending' => 'Ожидает подтверждения',
                'processing' => 'В обработке',
                'shipped' => 'Отправлен',
                'delivered' => 'Доставлен',
                'cancelled' => 'Отменён',
            ]
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.recipe.category']);

        return Inertia::render('Admin/Orders/Show', [
            'order' => $order
        ]);
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
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

            // Если заказ был отменен, но теперь активируется - убираем товары со склада
            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->recipe && $item->recipe->quantity >= $item->quantity) {
                        $item->recipe->decrement('quantity', $item->quantity);
                    } else {
                        throw new \Exception("Недостаточно товара: {$item->recipe->name}");
                    }
                }
                $order->cancelled_at = null;
            }

            $order->update([
                'status' => $newStatus
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Статус заказа успешно обновлен');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->withErrors([
                'error' => 'Ошибка при обновлении статуса: ' . $e->getMessage()
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
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', 'Информация о заказе успешно обновлена');
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            // Возвращаем товары на склад перед удалением
            foreach ($order->items as $item) {
                if ($item->recipe) {
                    $item->recipe->increment('quantity', $item->quantity);
                }
            }

            $order->delete();

            DB::commit();

            return redirect()->route('admin.orders.index')->with('success', 'Заказ успешно удален');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->withErrors([
                'error' => 'Ошибка при удалении заказа: ' . $e->getMessage()
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
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'revenue' => Order::where('status', 'delivered')->sum('total'),
        ];

        return Inertia::render('Admin/Orders/Statistics', [
            'stats' => $stats
        ]);
    }
}