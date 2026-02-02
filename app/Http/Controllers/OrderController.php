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
    public function index(Request $request)
    {
        // Sadece giriş yapmış kullanıcılar sipariş geçmişini görebilir
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Sipariş geçmişinizi görmek için giriş yapmalısınız!');
        }

        $query = Order::where('user_id', Auth::id())->with('statusHistory');

        // Sipariş numarası ile arama
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('order_number', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('id', 'LIKE', "%{$searchTerm}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        // Arama terimini view'a gönder
        $searchTerm = $request->search ?? '';

        return view('orders.index', compact('orders', 'searchTerm'));
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
