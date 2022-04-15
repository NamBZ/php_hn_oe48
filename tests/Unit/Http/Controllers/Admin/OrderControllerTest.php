<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Http\Controllers\Admin\OrderController;
use App\Models\Order;
use App\Models\Product;
use App\Notifications\UpdateOrderStatus;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Tests\TestCase;
use Mockery as m;

class OrderControllerTest extends TestCase
{
    protected $orderController;
    protected $orderRepository;
    protected $productRepository;
    protected $userRepository;
    protected $orders;
    protected $request;
    protected $eventNotify;

    public function setUp() : void
    {
        parent::setUp();
        $this->orderRepository = m::mock(OrderRepositoryInterface::class)->makePartial();
        $this->productRepository = m::mock(ProductRepositoryInterface::class)->makePartial();
        $this->userRepository = m::mock(UserRepositoryInterface::class)->makePartial();
        $this->orderController = new OrderController(
            $this->orderRepository,
            $this->productRepository,
            $this->userRepository
        );
        $this->request = m::mock(Request::class);
    }

    public function tearDown() : void
    {
        m::close();
        unset($this->orderController);
        parent::tearDown();
    }

    public function testIndexView()
    {
        config()->set('pagination.per_page', 20);
        $orders = m::mock(Order::class)->makePartial();
        $this->orderRepository->shouldReceive('paginate')->andReturn($orders);
        
        $view = $this->orderController->index();

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('dashboards.admin.orders.index', $view->getName());
        $this->assertArrayHasKey('orders', $view->getData());
    }

    public function testViewOrder()
    {
        $orders = m::mock(Order::class)->makePartial();
        $orders->id = 1;

        $this->orderRepository->shouldReceive('findOrFail')->andReturn($orders);
        $this->orderRepository->shouldReceive('where')->andReturn(true);
        $this->orderRepository->shouldReceive('getCustomer')->andReturn(true);
        $this->orderRepository->shouldReceive('getOrderItems')->andReturn(true);
        $this->orderRepository->shouldReceive('getShipping')->andReturn(true);
        
        $view = $this->orderController->viewOrder($orders->id);

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('dashboards.admin.orders.viewOrder', $view->getName());
        $this->assertArrayHasKey('orders', $view->getData());
    }

    public function testUpdateFailWhenOrderCompleteOrCancel()
    {
        $request = new Request([
            'status' => 0,
            'reason_canceled' => null,
        ]);
        $order = Order::factory()->make([
            'id' => 1,
            'status' => 3,
        ]);
        $this->orderRepository->shouldReceive('find')->andReturn($order);
        $request->setLaravelSession(session());

        $response = $this->orderController->update($request, $order->id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.orders.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('error', session()->all());
    }

    public function testUpdateSuccess()
    {
        $request = new Request([
            'status' => 1,
            'reason_canceled' => null,
        ]);
        $order = Order::factory()->make([
            'id' => 1,
            'status' => 0,
        ]);
        $eventNotify = new UpdateOrderStatus($order);
        $this->orderRepository->shouldReceive('find')->andReturn($order);
        $this->orderRepository->shouldReceive('update')->andReturn(true);
        $this->userRepository->shouldReceive('sendNotify')->andReturn($eventNotify);

        $response = $this->orderController->update($request, $order->id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.orders.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('success', session()->all());
    }
}
