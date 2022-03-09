<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\OrderStatus;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('dashboards.user.index');
    }

    public function purchase()
    {
        $user = Auth::user();
        $orders = $user->orders()->paginate(config('pagination.per_page'));

        return view('user.order.purchase', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    public function orderDetail($id)
    {
        $user = Auth::user();
        $order = $user->orders()->with('orderItems')->with('shipping')->findOrFail($id);

        return view('user.order.details', [
            'user' => $user,
            'order' => $order,
        ]);
    }

    public function orderCancel(Request $request, $id)
    {
        $user = Auth::user();
        $order = $user->orders()->findOrFail($id);

        $order->status = OrderStatus::CANCELED;

        if ($request->reason_canceled) {
            $order->reason_canceled = $request->reason_canceled;
        }

        if ($order->save()) {
            return redirect()->route('user.purchase')->with('success', __('Order is canceled'));
        }

        return redirect()->back()->with('error', __('Failed to cancel this order'));
    }
}
