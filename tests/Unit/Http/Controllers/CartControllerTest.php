<?php

namespace Tests\Unit\Http\Controllers;

use DB;
use Exception;
use Tests\TestCase;
use Mockery as m;
use Mockery\MockInterface;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;
use App\Models\Product;
use App\Enums\OrderStatus;
use App\Http\Controllers\CartController;
use App\Http\Requests\Shipping\StoreRequest;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\Shipping\ShippingRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CartControllerTest extends TestCase
{
    protected $controller;

    protected $orderRepo;

    protected $orderItemRepo;

    protected $shippingRepo;

    protected $productRepo;

    public function setup() : void
    {
        parent::setUp();

        $this->orderRepo = m::mock(OrderRepositoryInterface::class)->makePartial();
        $this->orderItemRepo = m::mock(OrderItemRepositoryInterface::class)->makePartial();
        $this->shippingRepo = m::mock(ShippingRepositoryInterface::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $this->productRepo = m::mock(ProductRepositoryInterface::class)->makePartial();

        $this->controller = new CartController(
            $this->orderRepo,
            $this->orderItemRepo,
            $this->shippingRepo,
            $this->productRepo
        );
    }

    public function tearDown() : void
    {
        m::close();
        unset($this->controller);
        parent::tearDown();
    }

    public function testIndexView()
    {
        $view = $this->controller->index();

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('user.cart', $view->getName());
    }

    // Test số lượng còn trong kho < số lượng khách chọn
    public function testAddCartFailByQuantity()
    {
        $request = new Request();
        $request->merge([
            'id' => 1,
            'quantity' => 2,
        ]);

        $product = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
            'quantity' => 0,
        ]);

        $this->productRepo->shouldReceive('findOrFail')->andReturn($product);

        $response = $this->controller->add($request);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('error', session()->all());
    }

    // Thêm giỏ hàng thành công khi cart rỗng
    public function testAddCartSuccessWhenNotHasCartSession()
    {
        $request = new Request();
        $request->merge([
            'id' => 1,
            'quantity' => 2,
        ]);
        $request->setLaravelSession(session());

        $product = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
        ]);

        $this->productRepo->shouldReceive('findOrFail')->andReturn($product);

        $response = $this->controller->add($request);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('cart', session()->all());
        $this->assertArrayHasKey('success', session()->all());

        $product->selected_quantity = $request->quantity;
        $this->assertEqualsCanonicalizing($product, session()->get('cart')[$product->id]);
    }

    // Thêm thành công khi cart đã tồn tại sản phẩm đó
    public function testAddCartSuccessCartSessionContainProduct()
    {
        $request = new Request();
        $request->merge([
            'id' => 1,
            'quantity' => 2,
        ]);

        $product = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
        ]);

        $product->selected_quantity = 5;
        $cart[$product->id] = $product;
        session()->put('cart', $cart);

        $request->setLaravelSession(session());

        $this->productRepo->shouldReceive('findOrFail')->andReturn($product);

        $response = $this->controller->add($request);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('cart', session()->all());
        $this->assertArrayHasKey('success', session()->all());

        $product->selected_quantity += $request->quantity;
        $this->assertEqualsCanonicalizing($product, session()->get('cart')[$product->id]);
    }

    // Thêm thành công khi cart chưa tồn tại sản phẩm đó
    public function testAddCartSuccessCartSessionNotContainProduct()
    {
        $request = new Request();
        $request->merge([
            'id' => 2,
            'quantity' => 2,
        ]);

        $productAdded = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
        ]);

        $productWantAdd = Product::factory()->make([
            'id' => 2,
            'category_id' => 1,
        ]);

        $productAdded->selected_quantity = 5;
        $cart[$productAdded->id] = $productAdded;
        session()->put('cart', $cart);

        $request->setLaravelSession(session());

        $this->productRepo->shouldReceive('findOrFail')->andReturn($productWantAdd);

        $response = $this->controller->add($request);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('cart', session()->all());
        $this->assertArrayHasKey('success', session()->all());

        $productWantAdd->selected_quantity = $request->quantity;
        $this->assertEqualsCanonicalizing($productWantAdd, session()->get('cart')[$productWantAdd->id]);
        $this->assertEqualsCanonicalizing($productAdded, session()->get('cart')[$productAdded->id]);
    }

    // Thêm lỗi khi cart đã có sản phẩm đó nhưng số lượng thêm không đủ
    public function testAddCartFailQuantityCartSessionContainProduct()
    {
        //Thêm 1 cái, đã có 100 cái trong giỏ nhưng quantity chỉ là 100
        $request = new Request();
        $request->merge([
            'id' => 1,
            'quantity' => 1,
        ]);

        $product = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
            'quantity' => 100,
        ]);

        $cart[$product->id] = $product;
        $cart[$product->id]['selected_quantity'] = 100;
        session()->put('cart', $cart);

        $request->setLaravelSession(session());

        $this->productRepo->shouldReceive('findOrFail')->andReturn($product);

        $response = $this->controller->add($request);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('cart', session()->all());
        $this->assertArrayHasKey('warning', session()->all());

        $this->assertEqualsCanonicalizing($product, session()->get('cart')[$product->id]);
    }

    // Update lỗi khi cart rỗng
    public function testUpdateCartFailIfCartEmpty()
    {
        $request = new Request();
        $request->merge([
            'qty' => [
                'id' => 1,
            ],
        ]);

        $product = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
            'quantity' => 100,
        ]);

        $request->setLaravelSession(session());

        $response = $this->controller->update($request);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('error', session()->all());
    }

    // Update thành công
    public function testUpdateCartSuccess()
    {
        $product = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
            'quantity' => 100,
        ]);

        $request = new Request();
        $request->merge([
            'qty' => [
                $product->id => 10, // thêm 10 món
            ],
        ]);

        $cart[$product->id] = $product;
        $cart[$product->id]['selected_quantity'] = 5; // đã có 5 món
        session()->put('cart', $cart);

        $request->setLaravelSession(session());

        $response = $this->controller->update($request);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('success', session()->all());

        $product->selected_quantity = $request->qty[$product->id];
        $this->assertEqualsCanonicalizing($product, session()->get('cart')[$product->id]);
    }

    // Update lỗi 1 phần khi có quantity vượt quá
    public function testUpdateCartFailByQuantity()
    {
        $product = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
            'quantity' => 100,
        ]);

        $request = new Request();
        $request->merge([
            'qty' => [
                $product->id => 101, // update thành 101 món
            ],
        ]);

        $cart[$product->id] = $product;
        $cart[$product->id]['selected_quantity'] = 2; // đã có 2 món
        session()->put('cart', $cart);

        $request->setLaravelSession(session());

        $response = $this->controller->update($request);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('warning', session()->all());

        $product->selected_quantity = $cart[$product->id]['selected_quantity'];
        $this->assertEqualsCanonicalizing($product, session()->get('cart')[$product->id]);
    }

    // Xóa thành công
    public function testDeleteProductFromCartSuccess()
    {
        $product = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
            'quantity' => 100,
        ]);

        $request = new Request();

        $cart[$product->id] = $product;
        $cart[$product->id]['selected_quantity'] = 2; // đã có 2 món
        session()->put('cart', $cart);

        $request->setLaravelSession(session());

        $response = $this->controller->delete($request, $product->id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('success', session()->all());

        $this->assertEmpty(session()->get('cart'));
    }

    // Checkout thành công
    public function testCheckoutSuccess()
    {
        $product = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
            'quantity' => 100,
        ]);

        $user = User::factory()->make();

        Auth::shouldReceive('user')->once()->andreturn($user);

        $request = new Request();

        $cart[$product->id] = $product;
        $cart[$product->id]['selected_quantity'] = 2; // đã có 2 món
        session()->put('cart', $cart);

        $request->setLaravelSession(session());

        $view = $this->controller->checkout();

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('user.checkout', $view->getName());
        $this->assertArrayHasKey('user', $view->getData());
    }

    // Checkout thất bại khi không có cart
    public function testCheckoutFail()
    {
        $product = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
            'quantity' => 100,
        ]);

        $user = User::factory()->make();

        Auth::shouldReceive('user')->once()->andreturn($user);

        $request = new Request();

        $request->setLaravelSession(session());

        $response = $this->controller->checkout();

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('error', session()->all());

        $this->assertEmpty(session()->get('cart'));
    }

    // Order fail khi cart rỗng
    public function testOrderFail()
    {
        $request = new StoreRequest();

        $request->setLaravelSession(session());

        $response = $this->controller->order($request);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('cart'), $response->headers->get('Location'));
        $this->assertArrayHasKey('error', session()->all());

        $this->assertEmpty(session()->get('cart'));
    }

    // Order thành công
    public function testOrderSuccess()
    {
        DB::shouldReceive('beginTransaction');

        $product = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
            'quantity' => 100,
        ]);
        // Fake request & session
        $request = new StoreRequest();
        $ship_info = [
            'name' => 'Hoang Van A',
            'address' => 'Nguyen Chi Thanh, Ba Dinh, Ha Noi',
            'phone' => '0918273645',
            'note' => '',
        ];

        $cart[$product->id] = $product;
        $cart[$product->id]['selected_quantity'] = 2; // đã có 2 món
        session()->put('cart', $cart);

        session()->put('grandTotal', 2000000);

        $request = m::mock(StoreRequest::class);
        $request->shouldReceive('validated')->andReturn($ship_info);
        $request->shouldReceive('session')->andReturn(session());

        // Fake auth
        $user = User::factory()->make();
        Auth::shouldReceive('id')->once()->andreturn($user->id);

        // Mock
        $this->productRepo->shouldReceive('find')->andReturn($product);
        $order = Order::factory()->make();
        $this->orderRepo->shouldReceive('create')->andReturn($order);
        $this->orderItemRepo->shouldReceive('create')->andReturn(true);
        $this->shippingRepo->shouldReceive('create')->andReturn(true);
        $this->productRepo->shouldReceive('updateProductQuantity')->andReturn(true);

        DB::shouldReceive('commit');

        $view = $this->controller->order($request);

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('user.checkoutSuccess', $view->getName());
        $this->assertArrayHasKey('order', $view->getData());

        $this->assertEmpty(session()->get('cart'));
    }

    // Order Order fail khi số lượng trong kho không đủ
    public function testOrderFailQuantity()
    {
        DB::shouldReceive('beginTransaction');

        $product = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
            'quantity' => 100,
        ]);
        // Fake request & session
        $request = new StoreRequest();
        $ship_info = [
            'name' => 'Hoang Van A',
            'address' => 'Nguyen Chi Thanh, Ba Dinh, Ha Noi',
            'phone' => '0918273645',
            'note' => '',
        ];

        $cart[$product->id] = $product;
        $cart[$product->id]['selected_quantity'] = 102; // Số lượng lớn hơn kho
        session()->put('cart', $cart);

        session()->put('grandTotal', 2000000);

        $request = m::mock(StoreRequest::class);
        $request->shouldReceive('validated')->andReturn($ship_info);
        $request->shouldReceive('session')->andReturn(session());

        // Fake auth
        $user = User::factory()->make();
        Auth::shouldReceive('id')->once()->andreturn($user->id);

        // Mock
        $this->productRepo->shouldReceive('find')->andReturn($product);
        $order = Order::factory()->make();
        $this->orderRepo->shouldReceive('create')->andReturn($order);
        $this->orderItemRepo->shouldReceive('create')->andReturn(true);
        $this->shippingRepo->shouldReceive('create')->andReturn(true);
        $this->productRepo->shouldReceive('updateProductQuantity')->andReturn(true);

        DB::shouldReceive('rollback');

        $response = $this->controller->order($request);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('cart'), $response->headers->get('Location'));
        $this->assertArrayHasKey('error', session()->all());
        $this->assertArrayHasKey('cart', session()->all());
    }

    // Order fail Eception
    public function testOrderFailException()
    {
        DB::shouldReceive('beginTransaction');

        $product = Product::factory()->make([
            'id' => 1,
            'category_id' => 1,
            'quantity' => 100,
        ]);
        // Fake request & session
        $request = new StoreRequest();
        $ship_info = [
            'name' => 'Hoang Van A',
            'address' => 'Nguyen Chi Thanh, Ba Dinh, Ha Noi',
            'phone' => '0918273645',
            'note' => '',
        ];

        $cart[$product->id] = $product;
        $cart[$product->id]['selected_quantity'] = 1; // Số lượng lớn hơn kho
        session()->put('cart', $cart);

        session()->put('grandTotal', 2000000);

        $request = m::mock(StoreRequest::class);
        $request->shouldReceive('validated')->andReturn($ship_info);
        $request->shouldReceive('session')->andReturn(session());

        // Fake auth
        $user = User::factory()->make();
        Auth::shouldReceive('id')->once()->andreturn($user->id);

        // Mock
        $this->productRepo->shouldReceive('find')->andReturn($product);
        $order = Order::factory()->make();
        $this->orderRepo->shouldReceive('create')->andReturn($order);
        $this->orderItemRepo->shouldReceive('create')->andReturn(true);
        $this->shippingRepo->shouldReceive('create')->andThrow(new Exception()); //exception
        $this->productRepo->shouldReceive('update')->andReturn(true);

        DB::shouldReceive('rollback');

        $response = $this->controller->order($request);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('error', session()->all());
        $this->assertArrayHasKey('cart', session()->all());
    }
}
