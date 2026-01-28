<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        }

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['user', 'statusHistory.updatedBy'])->findOrFail($id);

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        }

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,preparing,shipped,delivered,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->order_status;
        $newStatus = $request->status;

        // Durumu güncelle
        $order->update([
            'order_status' => $newStatus,
        ]);

        // Durum geçmişine ekle
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $newStatus,
            'notes' => $request->notes ?? "Durum '{$oldStatus}'den '{$newStatus}'ye güncellendi",
            'updated_by' => Auth::id(),
        ]);

        // Ödeme durumunu otomatik güncelle
        if ($newStatus === 'delivered' && $order->payment_status === 'pending') {
            $order->update(['payment_status' => 'paid']);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Sipariş durumu güncellendi!',
                'data' => $order->load('statusHistory')
            ]);
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Sipariş durumu başarıyla güncellendi!');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, string $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => $request->payment_status,
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Ödeme durumu güncellendi!',
                'data' => $order
            ]);
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Ödeme durumu başarıyla güncellendi!');
    }
}
