<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\RatingStatus;
use App\Http\Requests\Rating\RatingRequest;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\Rating\RatingRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RatingController extends Controller
{
    protected $orderRepo;

    protected $orderItemRepo;

    protected $ratingRepo;

    protected $productRepo;

    public function __construct(
        OrderRepositoryInterface $orderRepo,
        OrderItemRepositoryInterface $orderItemRepo,
        RatingRepositoryInterface $ratingRepo,
        ProductRepositoryInterface $productRepo
    ) {
        $this->orderRepo = $orderRepo;
        $this->orderItemRepo = $orderItemRepo;
        $this->ratingRepo = $ratingRepo;
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        $user = Auth::user();
        $orders = $this->orderRepo->getCompletedOrdersOfAuthUser();

        return view('user.rating.index', compact('orders', 'user'));
    }

    public function showAllOrder($id)
    {
        $user = Auth::user();
        $orderInfo = $this->orderRepo->getOrderDetailsOfAuthUser($id);
        $orderItems = $orderInfo->orderItems()->with('product', 'rating')->get();
        
        return view('user.rating.listOrder', [
            'user' => $user,
            'orderInfo' => $orderInfo,
            'orderItems' => $orderItems,
        ]);
    }

    public function addRating(RatingRequest $request, $id)
    {
        $orderItem = $this->orderItemRepo->findOrFail($id);

        if (!Gate::allows('add-rating', $orderItem->order)) {
            return redirect()->back()
                ->with('error', __('Can not access'));
        }

        $this->orderItemRepo->blockRating($id);

        $rating = $this->ratingRepo->updateOrCreate(
            [
                'order_item_id' => $id,
                'product_id' => $orderItem->product_id,
                'order_id' => $orderItem->order_id,
            ],
            [
                'rate' => $request->rate,
                'comment' => $request->comment,
            ]
        );

        // Update avg rate
        $avg_rate = $this->ratingRepo->avgRate($orderItem->product_id);
        $avg_rate = round($avg_rate * 2) / 2; // Round to the Nearest 0.5 (1.0, 1.5, 2.0, 2.5, etc.)
        $this->productRepo->updateAvg(
            $orderItem->product_id,
            $avg_rate
        );

        return redirect()->back()
            ->with('success', __('rating successfully'));
    }
}
