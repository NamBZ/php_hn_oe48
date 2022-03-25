<?php

namespace App\Repositories\OrderItem;

use App\Repositories\BaseRepository;
use App\Models\OrderItem;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return OrderItem::class;
    }
}
