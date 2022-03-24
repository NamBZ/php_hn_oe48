<?php

namespace Tests\Unit\Http\Controllers\Admin;

use Tests\TestCase;
use Mockery as m;
use Mockery\MockInterface;
use App\Models\User;
use App\Enums\UserRole;
use App\Http\Controllers\Admin\UserController;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class UserControllerTest extends TestCase
{
    protected $controller;
    protected $repository;
    protected $storeRequest;
    protected $updateRequest;
    protected $users;
    protected $user;

    public function setup() : void
    {
        parent::setUp();
        $this->repository = m::mock(UserRepositoryInterface::class)->makePartial();
        $this->controller = new UserController($this->repository);
        $this->storeRequest = m::mock(StoreRequest::class);
        $this->updateRequest = m::mock(UpdateRequest::class);
        $this->users = User::factory()->count(20)->make();
        $this->user = User::factory()->make();
        $this->user->id = 1;
    }

    public function tearDown() : void
    {
        m::close();
        unset($this->controller);
        parent::tearDown();
    }

    public function testIndexView()
    {
        config()->set('pagination.per_page', 20);

        $this->repository->shouldReceive('paginate')->andReturn($this->users);

        $view = $this->controller->index();

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('dashboards.admin.users.index', $view->getName());
        $this->assertArrayHasKey('users', $view->getData());
    }

    public function testCreateView()
    {
        $view = $this->controller->create();

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('dashboards.admin.users.create', $view->getName());
    }

    public function testStoreUserFail()
    {
        $this->storeRequest->shouldReceive('all')->andReturn($this->user);
        $this->storeRequest->shouldReceive('only')->andReturn($this->user);
        $this->repository->shouldReceive('create')->andReturn(false);

        $response = $this->controller->store($this->storeRequest);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('error', session()->all());
    }

    public function testStoreUserSuccess()
    {
        $this->storeRequest->shouldReceive('all')->andReturn($this->user);
        $this->storeRequest->shouldReceive('only')->andReturn($this->user);
        $this->repository->shouldReceive('create')->andReturn(true);

        $response = $this->controller->store($this->storeRequest);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.users.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('success', session()->all());
    }

    public function testEditView()
    {
        $this->repository->shouldReceive('findOrFail')->andReturn($this->user);

        $view = $this->controller->edit($this->user->id);

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('dashboards.admin.users.edit', $view->getName());
        $this->assertArrayHasKey('user', $view->getData());
    }

    public function testUpdateUserFail()
    {
        $this->updateRequest->shouldReceive('all')->andReturn($this->user);
        $this->updateRequest->shouldReceive('only')->andReturn($this->user);
        $this->repository->shouldReceive('findOrFail')->andReturn($this->user);
        $this->repository->shouldReceive('update')->andReturn(false);

        $response = $this->controller->update($this->updateRequest, $this->user->id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('error', session()->all());
    }

    public function testUpdateUserSuccess()
    {
        $this->updateRequest->shouldReceive('all')->andReturn($this->user);
        $this->updateRequest->shouldReceive('only')->andReturn($this->user);
        $this->repository->shouldReceive('findOrFail')->andReturn($this->user);
        $this->repository->shouldReceive('update')->andReturn(true);

        $response = $this->controller->update($this->updateRequest, $this->user->id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.users.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('success', session()->all());
    }

    public function testBlockUserFail1()
    {
        $this->user->role = UserRole::ADMIN;
        $this->repository->shouldReceive('findOrFail')->andReturn($this->user);

        $response = $this->controller->blockUser($this->user->id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('error', session()->all());
    }

    public function testBlockUserFail2()
    {
        $this->user->role = UserRole::USER;
        $this->repository->shouldReceive('findOrFail')->andReturn($this->user);
        $this->repository->shouldReceive('blockUser')->andReturn(false);

        $response = $this->controller->blockUser($this->user->id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('error', session()->all());
    }

    public function testBlockUserSuccess()
    {
        $this->user->role = UserRole::USER;
        $this->repository->shouldReceive('findOrFail')->andReturn($this->user);
        $this->repository->shouldReceive('blockUser')->andReturn(true);

        $response = $this->controller->blockUser($this->user->id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.users.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('success', session()->all());
    }

    public function testUnblockUserFail()
    {
        $this->repository->shouldReceive('findOrFail')->andReturn($this->user);
        $this->repository->shouldReceive('unblockUser')->andReturn(false);

        $response = $this->controller->unblockUser($this->user->id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertArrayHasKey('error', session()->all());
    }

    public function testUnblockUserSuccess()
    {
        $this->repository->shouldReceive('findOrFail')->andReturn($this->user);
        $this->repository->shouldReceive('unblockUser')->andReturn(true);

        $response = $this->controller->unblockUser($this->user->id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.users.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('success', session()->all());
    }

    public function testDestroyUser()
    {
        $this->repository->shouldReceive('findOrFail')->andReturn($this->user);
        $this->repository->shouldReceive('delete')->once();

        $response = $this->controller->destroy($this->user->id);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('admin.users.index'), $response->headers->get('Location'));
        $this->assertArrayHasKey('success', session()->all());
    }
}
