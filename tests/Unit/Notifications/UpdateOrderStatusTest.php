<?php

namespace Tests\Unit\Notifications;

use Mockery;
use Tests\TestCase;
use App\Models\Order;
use App\Notifications\UpdateOrderStatus;
use Illuminate\Notifications\Messages\MailMessage;

class UpdateOrderStatusTest extends TestCase
{
    protected $notification;
    protected $order;

    public function setUp() : void
    {
        parent::setUp();

        $this->order = Order::factory()->make();
        $this->order->id = 1;
        $this->order->user_id = 2;

        $this->notification = new UpdateOrderStatus($this->order);
    }

    public function tearDown() : void
    {
        Mockery::close();
        unset($this->order);
        unset($this->notification);
        parent::tearDown();
    }

    public function testVia()
    {
        $via = [
            'database',
            'broadcast',
            'mail',
        ];

        $this->assertEquals($via, $this->notification->via(User::class));
    }

    public function testRepresentationEmail()
    {
        $notifiable = Mockery::mock(Order::class)->makePartial();

        $result = $this->notification->toMail($notifiable);

        $this->assertInstanceOf(MailMessage::class, $result);
    }

    public function testRepresentationArray()
    {
        $notifiable = Mockery::mock(Order::class)->makePartial();

        $result = $this->notification->toArray($notifiable);

        $this->assertIsArray($result);
    }

    public function testRepresentationDatabase()
    {
        $notifiable = Mockery::mock(Order::class)->makePartial();

        $result = $this->notification->toDatabase($notifiable);

        $this->assertIsArray($result);
    }
}
