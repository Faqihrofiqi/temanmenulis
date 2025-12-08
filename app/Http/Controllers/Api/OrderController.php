<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Services\Qris\QrisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request, QrisService $qrisService): JsonResponse
    {
        $data = $request->validated();

        $service = Service::query()
            ->whereKey($data['service_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $order = $request->user()->orders()->create([
            'service_id' => $service->id,
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],
            'order_details' => $data['order_details'] ?? null,
            'metadata' => $data['metadata'] ?? null,
            'amount' => $service->price,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $order->load('service');

        return response()->json([
            'data' => $order,
            'qris' => $qrisService->generatePayload($order),
        ], 201);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        $this->ensureCanAccess($order, $request->user());

        $order->load(['service', 'payments']);

        return response()->json([
            'data' => $order,
        ]);
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): JsonResponse
    {
        abort_unless($request->user()->isAdmin(), 403);

        $data = $request->validated();
        $updates = [];

        if (array_key_exists('status', $data)) {
            $updates['status'] = $data['status'];
        }

        if (array_key_exists('payment_status', $data)) {
            $updates['payment_status'] = $data['payment_status'];
        }

        if (array_key_exists('internal_notes', $data)) {
            $updates['internal_notes'] = $data['internal_notes'];
        }

        if (array_key_exists('paid_at', $data)) {
            $updates['paid_at'] = $data['paid_at'];
        } elseif (($updates['payment_status'] ?? $order->payment_status) === 'settlement' && ! $order->paid_at) {
            $updates['paid_at'] = now();
        }

        $order->fill($updates)->save();

        return response()->json([
            'data' => $order->fresh(['service', 'payments']),
        ]);
    }

    private function ensureCanAccess(Order $order, User $user): void
    {
        if ($user->isAdmin()) {
            return;
        }

        abort_if($order->user_id !== $user->id && $order->customer_email !== $user->email, 403);
    }
}
