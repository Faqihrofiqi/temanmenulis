<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Service;
use App\Services\Qris\QrisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private readonly QrisService $qrisService)
    {
    }

    public function create(Request $request): View
    {
        $selectedService = null;

        if ($slug = $request->query('service')) {
            $selectedService = Service::where('slug', $slug)->first();
        }

        $services = Service::where('is_active', true)->orderBy('price')->get();

        return view('pages.orders.create', [
            'services' => $services,
            'selectedService' => $selectedService,
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $service = Service::findOrFail($request->integer('service_id'));
        $paymentChannel = $request->input('payment_channel', 'qris');

        $order = Order::create([
            'service_id' => $service->id,
            'user_id' => auth()->id(),
            'customer_name' => $request->input('customer_name'),
            'customer_email' => $request->input('customer_email'),
            'customer_phone' => $request->input('customer_phone'),
            'order_details' => $request->input('order_details'),
            'amount' => $service->effective_price,
            'metadata' => array_merge($request->input('metadata', []), [
                'source' => 'website',
                'payment_channel' => $paymentChannel,
                'applied_discount' => $service->has_active_discount ? [
                    'percentage' => $service->discount_percentage,
                    'label' => $service->discount_label,
                ] : null,
            ]),
        ]);

        $payload = $this->qrisService->generatePayload($order);

        $order->update([
            'payment_token' => $payload['qr_string'],
        ]);

        return redirect()->route('orders.payment', $order->order_number)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.');
    }

    public function payment(Order $order): View
    {
        $paymentData = $this->qrisService->generatePayload($order);

        return view('pages.orders.payment', [
            'order' => $order->load('service'),
            'paymentData' => $paymentData,
            'selectedChannel' => data_get($order->metadata, 'payment_channel', 'qris'),
        ]);
    }

    public function checkForm(): View
    {
        return view('pages.orders.check', [
            'order' => null,
            'orderNumber' => '',
        ]);
    }

    public function check(Request $request): View
    {
        $request->validate([
            'order_number' => ['required', 'string'],
        ]);

        $order = Order::where('order_number', $request->input('order_number'))
            ->with('service')
            ->first();

        return view('pages.orders.check', [
            'order' => $order,
            'orderNumber' => $request->input('order_number'),
        ]);
    }
}
