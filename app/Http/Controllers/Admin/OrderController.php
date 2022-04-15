<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Notifications\UpdateOrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    protected $orderRepository;
    protected $productRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $orders = $this->orderRepository
            ->paginate(config('pagination.per_page'));

        return view('dashboards.admin.orders.index', compact('orders'));
    }

    public function viewOrder($id)
    {
        $orders = $this->orderRepository->findOrFail($id);
        $orderInfo = $this->orderRepository->where('id', $id);
        $getCustomer = $this->orderRepository->getCustomer('user', $id);
        $orderItems = $this->orderRepository->getOrderItems('orderItems', $id);
        $getShipping = $this->orderRepository->getShipping('shipping', $id);
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
        $order = $this->orderRepository->find($id);
        if ($order->status == OrderStatus::COMPLETED || $order->status == OrderStatus::CANCELED) {
            return Redirect::route('admin.orders.index')
                ->with('error', __('Cannot change status of order successfully or canceled'));
        }
        if ($request->status == OrderStatus::CANCELED) {
            foreach ($this->orderRepository->relateToProduct($id) as $product) {
                $product_quantity_update = $product->quantity + $this->productRepository->getQuantity($product);
                $product_sold_update = $product->sold - $this->productRepository->getQuantity($product);
                $this->productRepository->updateProductQuantity(
                    $product->id,
                    $product_quantity_update,
                    $product_sold_update
                );
            }
            $this->orderRepository->update($id, [
                'reason_canceled' => $request->reason_canceled
            ]);
        }
        $this->orderRepository->update($id, [
            'status' => $request->status
        ]);

        // Send notification to user
        $eventNotify = new UpdateOrderStatus($order);
        $this->userRepository->sendNotify($order->user_id, $eventNotify);

        return Redirect::route('admin.orders.index')
            ->with('success', __('Update order status successfully'));
    }
}
