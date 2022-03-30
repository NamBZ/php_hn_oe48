<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Shipping\StoreRequest;
use App\Enums\OrderStatus;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\Shipping\ShippingRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;

class CartController extends Controller
{
    protected $orderRepo;

    protected $orderItemRepo;

    protected $shippingRepo;

    protected $productRepo;

    public function __construct(
        OrderRepositoryInterface $orderRepo,
        OrderItemRepositoryInterface $orderItemRepo,
        ShippingRepositoryInterface $shippingRepo,
        ProductRepositoryInterface $productRepo
    ) {
        $this->orderRepo = $orderRepo;
        $this->orderItemRepo = $orderItemRepo;
        $this->shippingRepo = $shippingRepo;
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        return view('user.cart');
    }

    public function add(Request $request)
    {
        $id = $request->id;
        $quantity = $request->quantity;
        $product = $this->productRepo->findOrFail($id);

        if ($quantity > $product->quantity || !is_numeric($product->quantity) || $product->quantity < 1) {
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
                        ->with('warning', __('You already have :selected_quantity quantity in cart.
                            Unable to add selected quantity to cart as it would exceed your purchase limit.', [
                                'selected_quantity' => $cart[$product->id]['selected_quantity'],
                        ]));
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
                ->with('warning', __('There are some items are the maximum quantity'));
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

    public function checkout()
    {
        $user = Auth::user();
        if (!session()->has('cart')) {
            return redirect()->route('cart')
                ->with('error', __('Please add items to your cart'));
        }

        return view('user.checkout', compact('user'));
    }

    public function order(StoreRequest $request)
    {
        if (!session()->has('cart')) {
            return redirect()->route('cart')
                ->with('error', __('Please add items to your cart'));
        }

        $cart = $request->session()->get('cart');

        $shipping_info = $request->validated();

        try {
            // Begin transaction
            DB::beginTransaction();

            // Insert order to get order_id
            $order = $this->orderRepo->create([
                'user_id' => Auth::id(),
                'order_code' => Str::random(8),
                'total_price' => $request->session()->get('grandTotal'),
                'status' => OrderStatus::NEW_ORDER,
            ]);

            // Insert order_items
            foreach ($cart as $product_id => $item) {
                $product = $this->productRepo->find($product_id);
                if (!$product || $product->quantity < $item->selected_quantity) {
                    DB::rollBack();

                    return redirect()->route('cart')
                        ->with('error', __('Some items have been updated or do not exist'));
                }

                // Insert order items
                $this->orderItemRepo->create([
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'price' => $item->retail_price,
                    'quantity' => $item->selected_quantity,
                ]);

                // Update product quantity
                $product_data_update = [
                    'quantity' => $product->quantity - $item->selected_quantity,
                    'sold' => $product->sold + $item->selected_quantity,
                ];
                $this->productRepo->update($product->id, $product_data_update);
            }

            // Insert shipping address
            $this->shippingRepo->create(array_merge(
                $shipping_info,
                [
                    'order_id' => $order->id,
                ],
            ));

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', __('There are some errors. Please try again later!'));
        }

        // Remove cart session
        $request->session()->forget('cart');

        return view('user.checkoutSuccess', [
            'order' => $order,
        ]);
    }
}
