<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;
use App\Models\Rating;
use Tests\ModelTestCase;

class UserTest extends ModelTestCase
{
    protected function initModel()
    {
        return new User();
    }

    public function testModelConfiguration()
    {
        $fillable = [
            'name',
            'email',
            'password',
            'phone',
            'avatar',
            'address',
            'role',
        ];

        $hidden = [
            'password',
            'remember_token',
        ];

        $casts = [
            'id' => 'int',
            'email_verified_at' => 'datetime',
        ];

        $this->runConfigurationAssertions(
            $this->model,
            [
                'fillable' => $fillable,
                'hidden' => $hidden,
                'casts' => $casts,
            ]
        );
    }

    public function testOrdersRelation()
    {
        $relation = $this->model->orders();
        $related = new Order();

        $this->assertHasManyRelation(
            $relation,
            $this->model,
            $related
        );
    }

    public function testOrderitemsRelation()
    {
        $relation = $this->model->orderItems();
        $key_related = 'order_id';
        $key_through = 'user_id';

        $this->assertHasManyThroughRelation(
            $relation,
            $this->model,
            $key_related,
            $key_through
        );
    }

    public function testShippingsRelation()
    {
        $relation = $this->model->shippings();
        $key_related = 'order_id';
        $key_through = 'user_id';

        $this->assertHasManyThroughRelation(
            $relation,
            $this->model,
            $key_related,
            $key_through
        );
    }

    public function testRatingsRelation()
    {
        $relation = $this->model->ratings();
        $key_related = 'order_id';
        $key_through = 'user_id';

        $this->assertHasManyThroughRelation(
            $relation,
            $this->model,
            $key_related,
            $key_through
        );
    }
}
