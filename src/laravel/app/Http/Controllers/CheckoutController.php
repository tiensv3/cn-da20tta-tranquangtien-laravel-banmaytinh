<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected $orderController;

    public function __construct(OrderController $orderController)
    {
        $this->orderController = $orderController;
    }

    public function show()
    {
        
        $cart = session('cart', []);
        $grandTotal = 0;
        $buynow = session('buynow', []);
        $buynowtotal = 0;

    foreach ($cart as $item) {
        if (isset($item['price'])) {
            $grandTotal += $item['quantity'] * $item['price'];
        }
    }

    foreach ($buynow as $item1) {
        if (isset($item1['price'])) {
            $buynowtotal += $item1['quantity'] * $item1['price'];
        }
    }

    return view('checkout', compact('cart', 'grandTotal','buynow','buynowtotal'));
    }


    public function process(Request $request)
    {
        

        $response = $this->orderController->createOrder($request);

        if ($response->getStatusCode() === 200) {
            return redirect()->route('thankyou')->with('success', 'Đơn hàng đã được xác nhận!');
        } else {
            // Handle the case where order creation fails
            return redirect()->route('checkout')->with('error', 'Có lỗi xảy ra khi xác nhận đơn hàng.');
        }
    }
}