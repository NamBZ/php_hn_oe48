<?php

namespace Tests\Unit\Models;

use App\Models\Shipping;
use App\Models\Order;
use Tests\ModelTestCase;

class ShippingTest extends ModelTestCase
{
    protected function initModel()
    {
        return new Shipping();
    }

    public function testModelConfiguration()
    {
        $fillable = [
            'order_id',
            'name',
            'address',
            'phone',
            'note',
        ];

        $this->runConfigurationAssertions(
            $this->model,
            [
                'fillable' => $fillable,
            ]
        );
    }

    public function testOrderRelation()
    {
        $relation = $this->model->order();
        $related = new Order();
        $key = 'order_id';

        $this->assertBelongsToRelation(
            $relation,
            $this->model,
            $related,
            $key
        );
    }
}
