<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderReviewController extends Controller
{
    public function store(Request $request, Order $order): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user, 403);
        abort_unless($order->user_id === $user->id || $order->customer_email === $user->email, 403);

        if ($order->status !== 'completed') {
            return back()->with('review_notice', 'Order belum selesai, review bisa dikirim setelah status selesai.');
        }

        if ($order->review) {
            return back()->with('review_notice', 'Terima kasih, review untuk order ini sudah tersimpan.');
        }

        $errorBag = 'review_'.$order->getKey();

        $validated = $request->validateWithBag($errorBag, [
            'rating' => ['required', 'integer', 'between:1,5'],
            'headline' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:20', 'max:1200'],
        ]);

        $order->review()->create([
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'headline' => $validated['headline'] ?? null,
            'message' => $validated['message'],
            'submitted_at' => now(),
        ]);

        return back()->with('review_success', 'Terima kasih, review kamu sudah kami terima!');
    }
}
