<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rating;
use Tests\ModelTestCase;

class OrderItemTest extends ModelTestCase
{
    protected function initModel()
    {
        return new OrderItem();
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions($this->model, [
            'fillable' => [
                'order_id',
                'product_id',
                'price',
                'quantity',
            ]
        ]);
    }

    public function testOrderRelation()
    {
        $this->assertBelongsToRelation(
            $this->model->order(),
            $this->model,
            new Order(),
            'order_id'
        );
    }

    public function testProductRelation()
    {
        $this->assertBelongsToRelation(
            $this->model->product(),
            $this->model,
            new Product(),
            'product_id'
        );
    }

    public function testRatingRelation()
    {
        $this->assertHasOneRelation(
            $this->model->rating(),
            $this->model,
            new Rating()
        );
    }
}
