<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Mockery as m;
use App\Http\Controllers\AdminController;
use App\Models\Order;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\View\View;

class AdminControllerTest extends TestCase
{
    protected $orderRepository;
    protected $order;
    protected $controller;

    public function setup() : void
    {
        parent::setUp();

        $this->orderRepository = m::mock(OrderRepositoryInterface::class)->makePartial();

        $this->controller = new AdminController(
            $this->orderRepository
        );
    }

    public function tearDown() : void
    {
        m::close();
        unset($this->controller);
        parent::tearDown();
    }

    public function testView()
    {
        $order = m::mock(Order::class)->makePartial();
        $order = [
            1 => 93500,
            2 => 324500,
            4 => 651970,
        ];
        $this->orderRepository->shouldReceive('showOrderSaleMonth')->andReturn($order);
        
        $response = $this->controller->index($this->orderRepository);

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('dashboards.admin.index', $response->getName());
        $this->assertArrayHasKey('chart', $response->getData());
    }
}
