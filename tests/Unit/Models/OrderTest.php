<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Shipping;
use App\Models\User;
use Tests\ModelTestCase;

class OrderTest extends ModelTestCase
{
    protected function initModel()
    {
        return new Order();
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions($this->model, [
            'fillable' => [
                'user_id',
                'order_code',
                'total_price',
                'status',
                'reason_canceled',
            ]
        ]);
    }

    public function testUserRelation()
    {
        $this->assertBelongsToRelation(
            $this->model->user(),
            $this->model,
            new User(),
            'user_id'
        );
    }

    public function testShippingRelation()
    {
        $this->assertHasOneRelation(
            $this->model->shipping(),
            $this->model,
            new Shipping()
        );
    }

    public function testOrderItemsRelation()
    {
        $this->assertHasManyRelation(
            $this->model->orderItems(),
            $this->model,
            new OrderItem()
        );
    }

    public function testRatingsRelation()
    {
        $this->assertHasManyRelation(
            $this->model->ratings(),
            $this->model,
            new Rating()
        );
    }

    public function testProductsRelation()
    {
        $this->assertBelongsToManyRelation(
            $this->model->products(),
            $this->model,
            new Product(),
            'order_items.order_id',
            'order_items.product_id'
        );
    }
}
