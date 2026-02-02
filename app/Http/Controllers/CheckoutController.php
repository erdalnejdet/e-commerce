<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
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

    // Checkout bilgilerini kaydet ve ödeme sayfasına yönlendir
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

        // Checkout bilgilerini session'a kaydet (henüz sipariş kaydedilmedi)
        session()->put('checkout_data', [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'postal_code' => $request->postal_code,
            'notes' => $request->notes,
            'user_id' => $user ? $user->id : null,
            'cart' => $cart,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);

        // Ödeme sayfasına yönlendir
        return response()->json([
            'success' => true,
            'message' => 'Bilgiler kaydedildi!',
            'redirect' => route('checkout.payment')
        ]);
    }

    // Ödeme işle (kredi kartı bilgileri ile)
    public function processPayment(Request $request)
    {
        $request->validate([
            'card_number' => 'required|string|min:16|max:19',
            'card_name' => 'required|string|max:255',
            'card_expiry_month' => 'required|string|size:2',
            'card_expiry_year' => 'required|string|size:4',
            'card_cvv' => 'required|string|min:3|max:4',
        ]);

        $checkoutData = session()->get('checkout_data');
        
        if (!$checkoutData) {
            return response()->json([
                'success' => false,
                'message' => 'Sipariş bilgileri bulunamadı!'
            ], 400);
        }

        $cart = session()->get('cart', []);
        
        // Sepet boşsa hata döndür
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Sepetiniz boş!'
            ], 400);
        }

        // Kredi kartı numarasını temizle (sadece son 4 haneyi sakla)
        $cardNumber = preg_replace('/\s+/', '', $request->card_number);
        $last4 = substr($cardNumber, -4);

        // Burada gerçek bir ödeme gateway entegrasyonu yapılabilir
        // Şimdilik simüle ediyoruz - her zaman başarılı
        $paymentSuccess = true; // Gerçek uygulamada ödeme gateway'den gelecek

        if (!$paymentSuccess) {
            return response()->json([
                'success' => false,
                'message' => 'Ödeme işlemi başarısız! Lütfen kart bilgilerinizi kontrol edin.'
            ], 400);
        }

        // Ödeme başarılı - siparişi kaydet
        $orderNumber = Order::generateOrderNumber();

        $order = Order::create([
            'user_id' => $checkoutData['user_id'],
            'order_number' => $orderNumber,
            'first_name' => $checkoutData['first_name'],
            'last_name' => $checkoutData['last_name'],
            'email' => $checkoutData['email'],
            'phone' => $checkoutData['phone'],
            'address' => $checkoutData['address'],
            'city' => $checkoutData['city'],
            'district' => $checkoutData['district'],
            'postal_code' => $checkoutData['postal_code'],
            'items' => $checkoutData['cart'],
            'subtotal' => $checkoutData['subtotal'],
            'tax' => $checkoutData['tax'],
            'total' => $checkoutData['total'],
            'payment_method' => 'credit_card',
            'payment_status' => 'paid', // Ödeme başarılı
            'order_status' => 'pending',
            'notes' => $checkoutData['notes'],
        ]);

        // İlk durum geçmişini kaydet
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'notes' => 'Sipariş oluşturuldu ve ödeme alındı',
            'updated_by' => null,
        ]);

        // Sipariş bilgilerini session'a kaydet
        session()->put('order', [
            'id' => $order->id,
            'order_number' => $order->order_number,
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
            'payment_status' => $order->payment_status,
            'notes' => $order->notes,
            'cart' => $checkoutData['cart'],
            'subtotal' => $checkoutData['subtotal'],
            'tax' => $checkoutData['tax'],
            'total' => $checkoutData['total'],
            'created_at' => $order->created_at->toDateTimeString()
        ]);

        // Checkout data'yı temizle
        session()->forget('checkout_data');
        // Sepeti temizle
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Ödeme başarılı! Siparişiniz oluşturuldu.',
            'redirect' => route('checkout.success')
        ]);
    }

    // Ödeme sayfası (kredi kartı için)
    public function payment()
    {
        $checkoutData = session()->get('checkout_data');
        
        if (!$checkoutData) {
            return redirect()->route('checkout.index')->with('error', 'Lütfen önce sipariş bilgilerinizi doldurun!');
        }

        return view('checkout-payment', compact('checkoutData'));
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
        
        // Kullanıcı bilgilerini al
        $user = Auth::user();
        
        // Sipariş bilgilerini göster
        return view('checkout-success', compact('order', 'user'));
    }
}
