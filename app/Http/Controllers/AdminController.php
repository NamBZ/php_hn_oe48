<?php

namespace App\Http\Controllers;

use App\Charts\OrderChart;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        $order = $this->orderRepository->showOrderSaleMonth();
        $data = [];
        for ($i = 0; $i < 12; $i++) {
            array_push($data, 0);
        }
        $data = array_combine(range(1, count($data)), $data);
        $dates = [
            __('January'),
            __('February'),
            __('March'),
            __('April'),
            __('May'),
            __('June'),
            __('July'),
            __('August'),
            __('September'),
            __('October'),
            __('November'),
            __('December'),
        ];
        
        $chart = new OrderChart;
        $chart->labels($dates);
        $chart->dataset(__('Revenue'), 'bar', array_values(array_replace($data, $order)))->options([
            'fill' => 'true',
            'backgroundColor' => '#51C1C0',
            'borderWidth' => 1,
            'hoverBorderWidth' => 3,
            'borderColor' => '#777',
        ]);

        return view('dashboards.admin.index', compact('chart'));
    }
}
