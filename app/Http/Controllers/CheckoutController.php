<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    // Checkout sayfasını göster
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Sepet boşsa sepet sayfasına yönlendir
        if (empty($cart)) {
            if (request()->wantsJson() || request()->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sepetiniz boş!',
                    'redirect' => route('cart.index')
                ], 400);
            }
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş!');
        }
        
        // Sepet toplamlarını hesapla
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = $subtotal * 0.18;
        $total = $subtotal + $tax;
        
        // Kullanıcı bilgilerini al
        $user = Auth::user();
        
        // AJAX isteği ise sadece özet HTML'i döndür
        if (request()->wantsJson() || request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            $orderSummaryHtml = view('partials.order-summary', compact('cart', 'subtotal', 'tax', 'total'))->render();
            return response()->json([
                'success' => true,
                'html' => $orderSummaryHtml,
                'cart_count' => count($cart)
            ]);
        }
        
        return view('checkout', compact('cart', 'subtotal', 'tax', 'total', 'user'));
    }

    // Siparişi işle
    public function process(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'payment_method' => 'required|in:cash_on_delivery,credit_card',
            'notes' => 'nullable|string|max:1000',
            'save_address' => 'nullable|boolean'
        ]);

        $cart = session()->get('cart', []);
        
        // Sepet boşsa hata döndür
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Sepetiniz boş!'
            ], 400);
        }

        // Sepet toplamlarını hesapla
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = $subtotal * 0.18;
        $total = $subtotal + $tax;

        // Kullanıcı giriş yapmışsa adresi kaydet
        $user = Auth::user();
        if ($user && $request->has('save_address')) {
            $user->update([
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'district' => $request->district,
                'postal_code' => $request->postal_code,
            ]);
        }

        // Siparişi veritabanına kaydet
        $order = Order::create([
            'user_id' => $user ? $user->id : null,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'postal_code' => $request->postal_code,
            'items' => $cart,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Sipariş bilgilerini session'a kaydet
        session()->put('order', [
            'id' => $order->id,
            'customer' => [
                'first_name' => $order->first_name,
                'last_name' => $order->last_name,
                'email' => $order->email,
                'phone' => $order->phone,
            ],
            'shipping' => [
                'address' => $order->address,
                'city' => $order->city,
                'district' => $order->district,
                'postal_code' => $order->postal_code,
            ],
            'payment_method' => $order->payment_method,
            'notes' => $order->notes,
            'cart' => $cart,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'created_at' => $order->created_at->toDateTimeString()
        ]);

        // Ödeme yöntemine göre yönlendir
        if ($request->payment_method === 'credit_card') {
            // Kredi kartı ödeme sayfasına yönlendir (ileride eklenecek)
            return response()->json([
                'success' => true,
                'message' => 'Sipariş oluşturuldu!',
                'redirect' => route('checkout.payment')
            ]);
        } else {
            // Kapıda ödeme için sipariş onay sayfasına yönlendir
            return response()->json([
                'success' => true,
                'message' => 'Sipariş oluşturuldu!',
                'redirect' => route('checkout.success')
            ]);
        }
    }

    // Ödeme sayfası (kredi kartı için)
    public function payment()
    {
        $order = session()->get('order');
        
        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'Sipariş bulunamadı!');
        }

        // Sepet toplamlarını hesapla
        $subtotal = 0;
        foreach($order['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = $subtotal * 0.18;
        $total = $subtotal + $tax;

        return view('checkout-payment', compact('order', 'subtotal', 'tax', 'total'));
    }

    // Sipariş başarı sayfası
    public function success()
    {
        $order = session()->get('order');
        
        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'Sipariş bulunamadı!');
        }

        // Sepeti temizle
        session()->forget('cart');
        
        // Sipariş bilgilerini göster
        return view('checkout-success', compact('order'));
    }
}
