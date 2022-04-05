<?php

namespace App\Repositories\OrderItem;

use App\Repositories\RepositoryInterface;

interface OrderItemRepositoryInterface extends RepositoryInterface
{
    public function blockRating($rating_id);
}
