<?php

namespace App\Repositories\OrderItem;

use App\Repositories\BaseRepository;
use App\Models\OrderItem;
use App\Enums\RatingStatus;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return OrderItem::class;
    }

    public function blockRating($rating_id)
    {
        $rating = $this->find($rating_id);
        $rating->rstatus = RatingStatus::BLOCK;

        if ($rating->save()) {
            return true;
        }

        return false;
    }
}
