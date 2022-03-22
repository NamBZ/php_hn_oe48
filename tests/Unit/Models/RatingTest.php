<?php

namespace Tests\Unit\Models;

use App\Models\Rating;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Tests\ModelTestCase;

class RatingTest extends ModelTestCase
{
    protected function initModel()
    {
        return new Rating();
    }

    public function testModelConfiguration()
    {
        $fillable = [
            'order_id',
            'product_id',
            'order_item_id',
            'rate',
            'comment',
        ];

        $this->runConfigurationAssertions(
            $this->model,
            [
                'fillable' => $fillable,
            ]
        );
    }

    public function testProductRelation()
    {
        $relation = $this->model->product();
        $related = new Product();
        $key = 'product_id';

        $this->assertBelongsToRelation(
            $relation,
            $this->model,
            $related,
            $key
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

    public function testOrderItemRelation()
    {
        $relation = $this->model->orderItem();
        $related = new OrderItem();
        $key = 'order_item_id';

        $this->assertBelongsToRelation(
            $relation,
            $this->model,
            $related,
            $key
        );
    }
}
