<?php

namespace Tests\Unit\Console\Commands;

use Mockery as m;
use Tests\TestCase;
use App\Console\Kernel;
use Illuminate\Console\Scheduling\Schedule;

class KernelTest extends TestCase
{
    public $kernelCommand;

    public function setUp(): void
    {
        parent::setUp();
        $this->kernelCommand = $this->app->make(Kernel::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->kernelCommand);
    }

    public function testSchedule()
    {
        $schedule = m::mock(Schedule::class)->makePartial();
        $schedule->shouldReceive('command->weeklyOn')->andReturn(true);

        $response = $this->kernelCommand->schedule($schedule);
        $this->assertNull($response);
    }
}
