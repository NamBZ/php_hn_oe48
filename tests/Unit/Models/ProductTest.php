<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rating;
use Tests\ModelTestCase;

class ProductTest extends ModelTestCase
{
    protected function initModel()
    {
        return new Product();
    }

    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions($this->model, [
            'fillable' => [
                'title',
                'category_id',
                'content',
                'description',
                'slug',
                'quantity',
                'image',
                'retail_price',
                'original_price',
            ],
            'casts' => [
                'id' => 'int',
                'deleted_at' => 'datetime',
            ],
        ]);
    }

    public function testCategoryRelation()
    {
        $this->assertBelongsToRelation(
            $this->model->category(),
            $this->model,
            new Category(),
            'category_id'
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

    public function testOrderItemsRelation()
    {
        $this->assertHasManyRelation(
            $this->model->orderItems(),
            $this->model,
            new OrderItem()
        );
    }

    public function testOrdersRelation()
    {
        $this->assertBelongsToManyRelation(
            $this->model->orders(),
            $this->model,
            new Order(),
            'order_items.product_id',
            'order_items.order_id'
        );
    }
}
