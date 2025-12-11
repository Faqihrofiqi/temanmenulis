<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Service;
use App\Services\Midtrans\SnapService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private readonly SnapService $snapService)
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

        $order = Order::create([
            'service_id' => $service->id,
            'user_id' => auth()->id(),
            'customer_name' => $request->input('customer_name'),
            'customer_email' => $request->input('customer_email'),
            'customer_phone' => $request->input('customer_phone'),
            'order_details' => $request->input('order_details'),
            'amount' => $service->effective_price,
            'metadata' => array_merge((array) $request->input('metadata', []), [
                'source' => 'website',
                'payment_method' => 'midtrans_snap',
                'applied_discount' => $service->has_active_discount ? [
                    'percentage' => $service->discount_percentage,
                    'label' => $service->discount_label,
                ] : null,
            ]),
        ]);

        $this->snapService->getOrCreateTransaction($order, [
            'payment_channel' => null, // Enable all payment methods via Snap
        ]);

        return redirect()->route('orders.payment', $order->order_number)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.');
    }

    public function payment(Order $order): View
    {
        $order->load('service');

        $snapData = $this->snapService->getOrCreateTransaction($order, [
            'payment_channel' => null, // Enable all payment methods
        ]);

        $snapExpiry = null;
        $expiresAt = data_get($snapData, 'expires_at');

        if ($expiresAt) {
            try {
                $snapExpiry = Carbon::parse($expiresAt);
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return view('pages.orders.payment', [
            'order' => $order,
            'snapData' => $snapData,
            'snapToken' => data_get($snapData, 'token'),
            'snapRedirectUrl' => data_get($snapData, 'redirect_url'),
            'snapExpiry' => $snapExpiry,
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
