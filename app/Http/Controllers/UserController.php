<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\OrderStatus;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;

class UserController extends Controller
{
    protected $orderRepo;

    protected $productRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        OrderRepositoryInterface $orderRepo,
        ProductRepositoryInterface $productRepo
    ) {
        $this->orderRepo = $orderRepo;
        $this->productRepo = $productRepo;
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('dashboards.user.index');
    }

    public function purchase()
    {
        $user = Auth::user();
        $orders = $this->orderRepo->getOrdersOfAuthUser();

        return view('user.order.purchase', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    public function orderDetail($id)
    {
        $user = Auth::user();
        $order = $this->orderRepo->getOrderDetailsOfAuthUser($id);

        return view('user.order.details', [
            'user' => $user,
            'order' => $order,
        ]);
    }

    public function orderCancel(Request $request, $id)
    {
        $user = Auth::user();
        $order = $this->orderRepo->getOrderDetailsOfAuthUser($id);

        if ($order->status != OrderStatus::NEW_ORDER &&
            $order->status != OrderStatus::IN_PROCCESS) {
            return redirect()->back()->with('error', __('Failed to cancel this order'));
        }

        $order_update = [
            'status' => OrderStatus::CANCELED,
        ];

        if ($request->reason_canceled) {
            $order_update['reason_canceled'] = $request->reason_canceled;
        }

        // Update product quantity when cancel
        foreach ($order->products as $product) {
            // Update product quantity
            $product_quantity_update = $product->quantity + $product->pivot->quantity;
            $product_sold_update = $product->sold - $product->pivot->quantity;

            $this->productRepo->updateProductQuantity(
                $product->id,
                $product_quantity_update,
                $product_sold_update
            );
        }

        if ($this->orderRepo->update($order->id, $order_update)) {
            return redirect()->route('user.purchase')->with('success', __('Order is canceled'));
        }

        return redirect()->back()->with('error', __('Failed to cancel this order'));
    }
}
