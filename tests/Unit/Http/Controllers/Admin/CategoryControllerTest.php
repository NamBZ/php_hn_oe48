<?php

namespace Tests\Unit\Http\Controllers\Admin;

use Tests\TestCase;
use Mockery as m;
use App\Http\Controllers\Admin\CategoryController;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;

class CategoryControllerTest extends TestCase
{
    protected $productController;
    protected $productRepository;
    protected $categoryRepository;
    protected $storeRequest;
    protected $updateRequest;
    protected $product;
    protected $category;

    public function setUp() : void
    {
        parent::setUp();
        $this->categoryRepository = m::mock(CategoryRepositoryInterface::class);
        $this->productRepository = m::mock(ProductRepositoryInterface::class);
        $this->categoryController = new CategoryController(
            $this->categoryRepository,
            $this->productRepository
        );
        $this->storeRequest = m::mock(StoreRequest::class);
        $this->updateRequest = m::mock(UpdateRequest::class);
        $this->categories = Category::factory()->count(10)->make();
        $this->category = Category::factory()->make();
        $this->category->id = 2;
    }

    public function tearDown() : void
    {
        m::close();
        unset($this->categoryController);
        parent::tearDown();
    }

    public function testIndexView()
    {
        config()->set('pagination.per_page', 20);

        $this->categoryRepository->shouldReceive('paginate')->andReturn($this->categories);
        
        $view = $this->categoryController->index();

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('dashboards.admin.categories.index', $view->getName());
        $this->assertArrayHasKey('categories', $view->getData());
    }

    public function testCreateView()
    {
        $this->categoryRepository->shouldReceive('getAll')->andReturn($this->categories);

        $view = $this->categoryController->create();

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('dashboards.admin.categories.create', $view->getName());
    }

    public function testStoreSuccess()
    {
        $this->storeRequest->shouldReceive('validated')->andReturn($this->category);
        $this->categoryRepository->shouldReceive('create')->andReturn(true);

        $response = $this->categoryController->store($this->storeRequest);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.categories.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('success', session()->all());
    }

    public function testEditView()
    {
        $this->categoryRepository->shouldReceive('findOrFail')->andReturn($this->category);
        $this->categoryRepository->shouldReceive('loadParent')->andReturn($this->categories);

        $view = $this->categoryController->edit($this->category->id);

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('dashboards.admin.categories.edit', $view->getName());
        $this->assertArrayHasKey('category', $view->getData());
    }

    public function testUpdateSuccess()
    {
        $this->updateRequest->shouldReceive('validated')->andReturn($this->category);
        $this->categoryRepository->shouldReceive('update')->andReturn(true);

        $response = $this->categoryController->update($this->updateRequest, $this->category->id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.categories.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('success', session()->all());
    }

    public function testDestroy()
    {
        $this->categoryRepository->shouldReceive('findOrFail')->andReturn($this->category);
        $this->productRepository->shouldReceive('updateCategoryIdOfProductWhenCategoryDeleted')
            ->andReturn($this->product);
        $this->categoryRepository->shouldReceive('getDefaultCategoryId');
        $this->categoryRepository->shouldReceive('delete')->once();

        $response = $this->categoryController->destroy($this->category->id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.categories.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('success', session()->all());
    }

    public function testDestroyFail()
    {
        $defaultCate = 1;
        $this->category->id = 1;
        $this->categoryRepository->shouldReceive('findOrFail')->andReturn($this->category);
        $this->productRepository->shouldReceive('updateCategoryIdOfProductWhenCategoryDeleted')
            ->andReturn($this->product);
        $this->categoryRepository->shouldReceive('getDefaultCategoryId')->andReturn($defaultCate);
        $this->categoryRepository->shouldReceive('delete')->andReturn(false);

        $response = $this->categoryController->destroy($this->category->id);

        $this->assertEquals($this->category->id, $defaultCate);
        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.categories.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('info', session()->all());
    }
}
