<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;
use Mockery as m;
use App\Console\Commands\SendOrderReportToAdmin;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendOrderReportToAdminTest extends TestCase
{
    protected $command;

    public function setUp(): void
    {
        parent::setUp();
        $this->command = new SendOrderReportToAdmin();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->command);
    }

    public function testSignature()
    {
        $signature = 'mail:orderReport';
        $this->assertEquals($signature, $this->command->signature);
    }

    public function testHandle()
    {
        Mail::fake();

        $orders = m::mock(OrderRepositoryInterface::class)->makePartial();
        $orders->id = 1;

        $user = m::mock(UserRepositoryInterface::class)->makePartial();
        $user->email = 'admin@gmail.com';

        $user->shouldReceive('findAdmin')->andReturn($user);
        $orders->shouldReceiVe('getOrderCompletedOfWeek')->andReturn();

        $response = $this->command->handle($orders, $user);
    }
}
