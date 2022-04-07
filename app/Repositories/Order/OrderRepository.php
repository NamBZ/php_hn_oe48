<?php

namespace App\Repositories\Order;

use App\Repositories\BaseRepository;
use App\Models\Order;
use App\Enums\OrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Order::class;
    }

    public function getOrdersOfAuthUser()
    {
        $orders = Auth::user()->orders()
            ->orderBy('created_at', 'DESC')
            ->paginate(config('pagination.per_page'));

        return $orders;
    }

    public function getCompletedOrdersOfAuthUser()
    {
        $orders = Auth::user()->orders()
            ->whereStatus(OrderStatus::COMPLETED)
            ->orderBy('created_at', 'DESC')
            ->paginate(config('pagination.per_page'));

        return $orders;
    }

    public function getOrderDetailsOfAuthUser($order_id)
    {
        $orders = Auth::user()->orders()->with('orderItems', 'shipping')->findOrFail($order_id);

        return $orders;
    }
    
    public function getCustomer($relation, $orderId)
    {
        return $this->findOrFail($orderId)->user()->get();
    }
    
    public function getOrderItems($relation, $orderId)
    {
        return $this->findOrFail($orderId)->orderItems()->with('product')->get();
    }
    
    public function getShipping($relation, $orderId)
    {
        return $this->findOrFail($orderId)->shipping()->get();
    }

    public function relateToProduct($id)
    {
        $order = $this->find($id);

        return $order->products;
    }

    public function getQuantity($order)
    {
        return $order->pivot->quantity;
    }

    public function showOrderSaleMonth()
    {
        return Order::whereYear('created_at', Carbon::now()->year)
            ->whereStatus(OrderStatus::COMPLETED)
            ->get()
            ->groupBy(
                function ($date) {
                    return (int) $date->created_at->format('m');
                }
            )->map(
                function ($item) {
                    return array_sum($item->pluck('total_price')->toArray());
                }
            )->toArray();
    }
}
