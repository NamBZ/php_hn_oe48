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
        $order = Order::findOrFail($id);
        $getOrder = $order->whereId($id)->get();
        $getCustomer = $order->load('user')->whereId($id)->get();
        $orderDetails = OrderItem::whereOrderId($id)->with('product')->get();
        $getShipping = Shipping::whereOrderId($id)->get();
        $orderStatusArray = [
            OrderStatus::NEW_ORDER => __('New Order'),
            OrderStatus::IN_PROCCESS => __('In Proccess'),
            OrderStatus::IN_SHIPPING => __('In Shipping'),
            OrderStatus::COMPLETED => __('Delivery Completed'),
            OrderStatus::CANCELED => __('Order Canceled'),
        ];

        return view('dashboards.admin.orders.viewOrder', [
            'orderDetails' => $orderDetails,
            'getCustomer' => $getCustomer,
            'getShipping' => $getShipping,
            'getOrder' => $getOrder,
            'orderStatusArray' => $orderStatusArray,
        ]);
    }

    public function update(Request $request, $id)
    {
        $orderDetails = OrderItem::findOrFail($id);
        $order = Order::whereId($orderDetails->order_id);
        $order->update(['status'=> $request->status]);

        return Redirect::route('admin.orders.index')
            ->with('success', __('Update order status successfully'));
    }
}
