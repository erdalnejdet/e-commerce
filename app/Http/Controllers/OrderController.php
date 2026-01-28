<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display user's order history
     */
    public function index()
    {
        // Sadece giriş yapmış kullanıcılar sipariş geçmişini görebilir
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Sipariş geçmişinizi görmek için giriş yapmalısınız!');
        }

        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display order details
     */
    public function show(string $id)
    {
        $order = Order::with('statusHistory')->findOrFail($id);

        // Kullanıcı sadece kendi siparişini görebilir (admin hariç)
        if (!Auth::user()->is_admin && $order->user_id !== Auth::id()) {
            abort(403, 'Bu siparişe erişim yetkiniz yok!');
        }

        return view('orders.show', compact('order'));
    }
}
