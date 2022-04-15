<?php

namespace Tests\Unit\Mail;

use App\Mail\ReportOrder;
use App\Models\Order;
use Mockery;
use Tests\TestCase;

class ReportOrderTest extends TestCase
{
    public $orders;
    public $report;

    public function setUp(): void
    {
        parent::setUp();
        $this->orders = Mockery::mock(Order::class)->makePartial();
        $this->report = new ReportOrder(
            $this->orders
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
        unset($this->report);
    }

    public function testBuild()
    {
        $response = $this->report->build();
        $this->assertInstanceOf(ReportOrder::class, $response);
    }
}
