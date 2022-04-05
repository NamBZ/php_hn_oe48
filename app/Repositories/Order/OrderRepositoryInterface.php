<?php

namespace App\Repositories\Order;

use App\Repositories\RepositoryInterface;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function getCompletedOrdersOfAuthUser();

    public function getOrderDetailsOfAuthUser($order_id);
}
