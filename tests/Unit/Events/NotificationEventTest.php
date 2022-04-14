<?php

namespace Tests\Unit\Events;

use Tests\TestCase;
use Mockery;
use App\Models\Order;
use App\Events\NotificationEvent;
use Illuminate\Broadcasting\PrivateChannel;

class NotificationEventTest extends TestCase
{
    public function testBroadcastChannel()
    {
        $order = new Order([
            'user_id' => 10,
        ]);

        $event = new NotificationEvent($order);
        $channel = $event->broadcastOn();

        $this->assertSame($order, $event->order);
        $this->assertInstanceOf(PrivateChannel::class, $channel);
        $this->assertEquals('private-order.10', $channel->name);
    }
}
