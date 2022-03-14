<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\RatingStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Http\Requests\Rating\RatingRequest;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RatingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->whereStatus(OrderStatus::COMPLETED)
            ->orderBy('created_at', 'DESC')
            ->paginate(config('pagination.per_page'));

        return view('user.rating.index', compact('orders', 'user'));
    }

    public function showAllOrder($id)
    {
        $user = Auth::user();
        $orderInfo = $user->orders()->findOrFail($id);
        $orderItems = $orderInfo->orderItems()->with('product', 'rating')->get();
        
        return view('user.rating.listOrder', [
            'user' => $user,
            'orderInfo' => $orderInfo,
            'orderItems' => $orderItems,
        ]);
    }

    public function addRating(RatingRequest $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);

        if (!Gate::allows('add-rating', $orderItem->order)) {
            return redirect()->back()
                ->with('error', __('Can not access'));
        }

        $orderItem->rstatus = RatingStatus::BLOCK;
        $orderItem->save();

        $rating = Rating::updateOrCreate(
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
        $product = $orderItem->product;
        $avg_rate = Rating::where('product_id', $product->id)->avg('rate');
        $avg_rate = round($avg_rate * 2) / 2; // Round to the Nearest 0.5 (1.0, 1.5, 2.0, 2.5, etc.)
        $product->avg_rate = $avg_rate;
        $product->save();

        return redirect()->back()
            ->with('success', __('rating successfully'));
    }
}
