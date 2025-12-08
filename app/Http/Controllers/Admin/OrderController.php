<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $paymentStatus = $request->query('payment_status');

        $orders = Order::query()
            ->with(['service', 'user'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($paymentStatus, fn ($query) => $query->where('payment_status', $paymentStatus))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'completed' => Order::where('status', 'completed')->count(),
        ];

        return view('admin.orders.index', [
            'orders' => $orders,
            'stats' => $stats,
            'selectedStatus' => $status,
            'selectedPaymentStatus' => $paymentStatus,
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load([
                'service',
                'user',
                'payments' => fn ($query) => $query->latest(),
            ]),
            'statusOptions' => ['pending', 'processing', 'paid', 'completed', 'cancelled'],
            'paymentOptions' => ['pending', 'waiting', 'settlement', 'cancel', 'expire', 'failed'],
        ]);
    }

    public function update(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $order->update([
            'status' => $request->input('status'),
            'payment_status' => $request->input('payment_status'),
            'requires_followup' => $request->boolean('requires_followup'),
            'internal_notes' => $request->input('internal_notes'),
        ]);

        if ($order->status === 'paid' && ! $order->paid_at) {
            $order->update(['paid_at' => now()]);
        }

        return back()->with('success', 'Detail transaksi diperbarui.');
    }
}
