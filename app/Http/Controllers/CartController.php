<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'size' => 'required',
            'flavor' => 'required',
            'flavor_name' => 'nullable',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable'
        ]);

        $cart = session()->get('cart', []);
        
        $cartItemId = $request->product_id . '_' . $request->size . '_' . $request->flavor;
        
        if(isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] += $request->quantity;
        } else {
            $cart[$cartItemId] = [
                'product_id' => $request->product_id,
                'name' => $request->name,
                'price' => $request->price,
                'size' => $request->size,
                'flavor' => $request->flavor,
                'flavor_name' => $request->flavor_name ?? $request->flavor,
                'quantity' => $request->quantity,
                'image' => $request->image
            ];
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Ürün sepete eklendi!',
            'cart_count' => count($cart),
            'cart_total' => $this->getCartTotal()
        ]);
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Sepet güncellendi!',
                'cart_total' => $this->getCartTotal()
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Ürün bulunamadı!']);
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Ürün sepetten çıkarıldı!',
                'cart_count' => count($cart),
                'cart_total' => $this->getCartTotal()
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Ürün bulunamadı!']);
    }

    public function clear()
    {
        session()->forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Sepet temizlendi!'
        ]);
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        return response()->json(['count' => count($cart)]);
    }

    private function getCartTotal()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }
}
