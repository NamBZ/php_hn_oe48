<?php

namespace App\Console\Commands;

use App\Mail\ReportOrder;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendOrderReportToAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:orderReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email to Admin notify the list of completed orders at 8am every Sunday';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(
        OrderRepositoryInterface $orderRepository,
        UserRepositoryInterface $userRepository
    ) {
        $reports = $orderRepository->getOrderCompletedOfWeek();
        Mail::to($userRepository->findAdmin())->send(new ReportOrder($reports));
    }
}
