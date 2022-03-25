<?php

namespace App\Repositories\Shipping;

use App\Repositories\BaseRepository;
use App\Models\Shipping;

class ShippingRepository extends BaseRepository implements ShippingRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Shipping::class;
    }
}
