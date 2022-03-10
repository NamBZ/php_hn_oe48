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
        $getOrder = Order::whereId($id)->get();
        $orderDetails = OrderItem::whereOrderId($id)->with('product', 'rating')->get();
        
        return view('user.rating.listOrder', [
            'user' => $user,
            'orderDetails' => $orderDetails,
            'getOrder' => $getOrder,
        ]);
    }

    public function addRating(RatingRequest $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->rstatus = RatingStatus::BLOCK;
        $orderItem->save();

        $rating = Rating::create([
            'rate' => $request->rate,
            'comment' => $request->comment,
            'order_item_id' => $id,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id
        ]);

        // Update avg rate
        $product = Product::findOrFail($request->product_id);
        $avg_rate = Rating::where('product_id', $request->product_id)->avg('rate');
        $avg_rate = round($avg_rate * 2) / 2; // Round to the Nearest 0.5 (1.0, 1.5, 2.0, 2.5, etc.)
        $product->avg_rate = $avg_rate;
        $product->save();

        return redirect()->back()
            ->with('success', __('rating successfully'));
    }
}
