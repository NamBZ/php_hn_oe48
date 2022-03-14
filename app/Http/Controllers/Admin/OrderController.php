<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'DESC')
            ->paginate(config('pagination.per_page'));

        return view('dashboards.admin.orders.index', compact('orders'));
    }

    public function viewOrder($id)
    {
        $orders = Order::findOrFail($id);
        $orderInfo = $orders->whereId($id)->get();
        $getCustomer = $orders->user()->get();
        $orderItems = $orders->orderItems()->with('product')->get();
        $getShipping = $orders->shipping()->get();
        $orderStatus = [
            OrderStatus::NEW_ORDER => __('New Order'),
            OrderStatus::IN_PROCCESS => __('In Proccess'),
            OrderStatus::IN_SHIPPING => __('In Shipping'),
            OrderStatus::COMPLETED => __('Delivery Completed'),
            OrderStatus::CANCELED => __('Order Canceled'),
        ];

        return view('dashboards.admin.orders.viewOrder', [
            'orders' => $orders,
            'orderItems' => $orderItems,
            'getCustomer' => $getCustomer,
            'getShipping' => $getShipping,
            'orderInfo' => $orderInfo,
            'orderStatus' => $orderStatus,
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::whereId($id)->first();
        if ($order->status == OrderStatus::COMPLETED || $order->status == OrderStatus::CANCELED) {
            return Redirect::route('admin.orders.index')
                ->with('error', __('Cannot change status of order successfully or canceled'));
        }
        if ($request->status == OrderStatus::CANCELED) {
            foreach ($order->products as $product) {
                $product->quantity += $product->pivot->quantity;
                $product->sold -= $product->pivot->quantity;
                $product->update();
            }
            $order->update(['reason_canceled' => $request->reason_canceled]);
        }
        $order->update(['status' => $request->status]);

        return Redirect::route('admin.orders.index')
            ->with('success', __('Update order status successfully'));
    }
}
