<?php

namespace App\Repositories\Order;

use App\Repositories\BaseRepository;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Auth;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Order::class;
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
        $orders = Auth::user()->orders()->findOrFail($order_id);

        return $orders;
    }
}
