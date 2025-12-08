<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateServicePricingRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    public function update(UpdateServicePricingRequest $request, Service $service): RedirectResponse
    {
        $data = $request->validated();

        $discountPercentage = $data['discount_percentage'] ?? null;
        if ($discountPercentage === null || (float) $discountPercentage <= 0) {
            $data['discount_percentage'] = null;
            $data['discount_label'] = null;
            $data['discount_ends_at'] = null;
        }

        $service->update([
            'price' => $data['price'],
            'delivery_days' => $data['delivery_days'],
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured', $service->is_featured),
            'discount_percentage' => $data['discount_percentage'],
            'discount_label' => $data['discount_label'] ?? null,
            'discount_ends_at' => $data['discount_ends_at'] ?? null,
        ]);

        return back()->with('success', 'Paket berhasil diperbarui.');
    }
}
