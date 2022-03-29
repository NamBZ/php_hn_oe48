<?php

namespace Tests\Unit\Http\Controllers\Admin;

use Tests\TestCase;
use Mockery as m;
use App\Http\Controllers\Admin\ProductController;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;

class ProductControllerTest extends TestCase
{
    protected $productController;
    protected $productRepository;
    protected $categoryRepository;
    protected $storeRequest;
    protected $updateRequest;
    protected $products;
    protected $product;
    protected $categories;
    protected $category;

    public function setUp() : void
    {
        parent::setUp();
        $this->categoryRepository = m::mock(CategoryRepositoryInterface::class);
        $this->productRepository = m::mock(ProductRepositoryInterface::class);
        $this->productController = new ProductController(
            $this->categoryRepository,
            $this->productRepository
        );
        $this->storeRequest = m::mock(StoreRequest::class);
        $this->updateRequest = m::mock(UpdateRequest::class);
    }

    public function tearDown() : void
    {
        m::close();
        unset($this->productController);
        parent::tearDown();
    }

    public function testIndexView()
    {
        config()->set('pagination.per_page', 20);

        $product = m::mock(Product::class)->makePartial();
        $product->id = 1;

        $this->productRepository->shouldReceive('paginate')->andReturn($product);
        
        $view = $this->productController->index();

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('dashboards.admin.products.index', $view->getName());
        $this->assertArrayHasKey('products', $view->getData());
    }

    public function testCreateView()
    {
        $this->categoryRepository->shouldReceive('getAll')->andReturn($this->categories);
        
        $view = $this->productController->create();

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('dashboards.admin.products.create', $view->getName());
    }

    public function testStoreSuccess()
    {
        $request = new StoreRequest(
            [
                'title' => 'Product Test',
                'category_id' => 1,
                'slug' => 'product-test',
                'quantity' => 100,
                'content' => 'Test Content',
                'description' => 'Test Desc',
                'retail_price' => 100000,
                'original_price' => 90000,
                'image' => UploadedFile::fake()->image('test.png')
            ]
        );
        $this->productRepository->shouldReceive('create')->andReturn(true);

        $response = $this->productController->store($request);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.products.create'), $response->headers->get('Location'));
        $this->assertArrayHasKey('success', session()->all());
    }

    public function testEditSuccess()
    {
        $category = m::mock(Category::class)->makePartial();
        $category->id = 1;
        $product = m::mock(Product::class)->makePartial();
        $product->id = 1;
        $this->productRepository->shouldReceive('findOrFail')->andReturn($product);
        $this->categoryRepository->shouldReceive('getAll')->andReturn($category);

        $view = $this->productController->edit($product->id);

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('dashboards.admin.products.edit', $view->getName());
        $this->assertArrayHasKey('product', $view->getData());
    }

    public function testUpdateSuccessWithImage()
    {
        $request = new UpdateRequest(
            [
                'title' => 'Product Test',
                'category_id' => 1,
                'slug' => 'product-test',
                'quantity' => 101,
                'content' => 'Test Content Update',
                'description' => 'Test Desc Update',
                'retail_price' => 100001,
                'original_price' => 90001,
                'image' => UploadedFile::fake()->image('test.png')
            ]
        );
        $product = m::mock(Product::class)->makePartial();
        $product->id = 1;

        $this->productRepository->shouldReceive('findOrFail')->andReturn($product);
        $this->productRepository->shouldReceive('update')->andReturn(true);
        File::shouldReceive('exists')->andReturn(true);
        File::shouldReceive('delete')->andReturn(true);

        $response = $this->productController->update($request, $product);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.products.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('success', session()->all());
    }

    public function testUpdateSuccessWithoutImage()
    {
        $request = new UpdateRequest(
            [
                'title' => 'Product Test Update',
                'category_id' => 2,
                'slug' => 'product-test-update',
                'quantity' => 101,
                'content' => 'Test Content Update',
                'description' => 'Test Desc Update',
                'retail_price' => 100001,
                'original_price' => 90001,
            ]
        );
        $product = m::mock(Product::class)->makePartial();
        $product->id = 1;

        $this->productRepository->shouldReceive('findOrFail')->andReturn($product);
        $this->productRepository->shouldReceive('update')->andReturn(true);

        $response = $this->productController->update($request, $product);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.products.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('success', session()->all());
    }

    public function testDeleteSuccess()
    {
        $id = 1;
        $product = m::mock(Product::class)->makePartial();

        $this->productRepository->shouldReceive('findOrFail')->andReturn($product);
        File::shouldReceive('exists')->andReturn(true);
        File::shouldReceive('delete')->andReturn(true);
        $this->productRepository->shouldReceive('delete')->once();

        $response = $this->productController->destroy($id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.products.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('success', session()->all());
    }
}
