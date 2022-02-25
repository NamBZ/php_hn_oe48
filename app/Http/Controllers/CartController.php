<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        return view('user.cart');
    }

    public function add(Request $request)
    {
        $id = $request->id;
        $quantity = $request->quantity;
        $product = Product::findOrFail($id);

        if ($quantity > $product->quantity || $product->quantity < 1) {
            return redirect()->back()
                ->with('error', __('The quantity you have selected is invalid'));
        }

        if ($request->session()->get('cart') == null) {
            $product->selected_quantity = $request->quantity;

            $cart[$product->id] = $product;

            $request->session()->put('cart', $cart);
        } else {
            $cart = $request->session()->get('cart');

            if (isset($cart[$product->id])) {
                if ($cart[$product->id]['selected_quantity'] + $quantity > $product->quantity) {
                    return redirect()->back()
                        ->with('alert', __('Product is sold out!'));
                }

                $cart[$product->id]['selected_quantity'] += $quantity;
            } else {
                $product->selected_quantity = $request->quantity;
                $cart[$product->id] = $product;
            }

            $request->session()->put('cart', $cart);
        }

        return redirect()->back()
            ->with('success', __('This product has been added to your cart'));
    }

    public function update(Request $request)
    {
        if ($request->qty == null || $request->session()->get('cart') == null) {
            return redirect()->back()
                ->with('error', __('Cart error'));
        }

        $cart = $request->session()->get('cart');
        $check_quantity = true;

        foreach ($request->qty as $product_id => $quantity) {
            if ($quantity > $cart[$product_id]['quantity'] || $quantity < 1) {
                $check_quantity = false;
            }

            if (isset($cart[$product_id]) && $check_quantity) {
                $cart[$product_id]['selected_quantity'] = $quantity;
            }
        }

        $request->session()->put('cart', $cart);

        if (!$check_quantity) {
            return redirect()->back()
                ->with('success', __('There are some items are the maximum quantity'));
        }

        return redirect()->back()
            ->with('success', __('Update successful'));
    }

    public function delete(Request $request, $id)
    {
        $cart = $request->session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);

            $request->session()->put('cart', $cart);
        }

        return redirect()->back()
            ->with('success', __('Remove successful'));
    }
}
