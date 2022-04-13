<?php

namespace App\Repositories\Order;

use App\Repositories\RepositoryInterface;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function getOrdersOfAuthUser();

    public function getCompletedOrdersOfAuthUser();

    public function getOrderDetailsOfAuthUser($order_id);

    public function getCustomer($relation, $orderId);

    public function getOrderItems($relation, $orderId);

    public function getShipping($relation, $orderId);

    public function relateToProduct($id);

    public function getQuantity($order);

    public function showOrderSaleMonth();

    public function getOrderCompletedOfWeek();
}
