<?php

namespace App\Http\Controllers\admin;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $product = Product::active()->get();
        session()->put('title', 'Manual Order');
        $service = Service::get();

        return view('admin-views.order.manualOrder', compact('product', 'service'));
    }

    public function addToCart(Request $request)
    {
        // dd($request);
        $cart = CartManager::add_to_cart($request);
        session()->forget('coupon_code');
        session()->forget('coupon_discount');

        return response()->json($cart);
    }

    public function updateNavCart()
    {
        return response()->json(['data' => view('layouts.backend.partials.cart')->render()]);
    }

    public function removeFromCart(Request $request)
    {
        $user = Helpers::get_admin();
        // dd($user);
        // session()->forget('offline_cart');
        if ($user == 'offline') {
            if (session()->has('offline_cart') == false) {
                session()->put('offline_cart', collect([]));
            }
            $cart = session('offline_cart');

            $new_collection = collect([]);
            foreach ($cart as $item) {
                if ($item['id'] != $request->key) {
                    $new_collection->push($item);
                }
            }

            session()->put('offline_cart', $new_collection);

            return response()->json($new_collection);
        } else {
            Cart::where(['id' => $request->key, 'customer_id' => auth('admin')->id()])->delete();
        }

        session()->forget('coupon_code');
        session()->forget('coupon_discount');
        session()->forget('shipping_method_id');

        return response()->json(['data' => view('layouts.backend.partials.cart')]);
    }
}
