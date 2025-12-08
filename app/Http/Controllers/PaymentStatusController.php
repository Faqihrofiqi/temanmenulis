<?php

namespace App\Http\Controllers;

use App\Models\Order;

class PaymentStatusController extends Controller
{
    public function show(Order $order)
    {
        return response()->json([
            'order_number' => $order->order_number,
            'status' => $order->payment_status,
            'order_status' => $order->status,
            'paid' => in_array($order->status, ['paid', 'processing', 'completed'], true),
        ]);
    }
}
