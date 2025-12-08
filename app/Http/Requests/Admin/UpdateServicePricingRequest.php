<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServicePricingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'price' => ['required', 'numeric', 'min:0'],
            'delivery_days' => ['required', 'integer', 'min:1', 'max:90'],
            'is_active' => ['required', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
            'discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:95'],
            'discount_label' => ['nullable', 'string', 'max:120'],
            'discount_ends_at' => ['nullable', 'date'],
        ];
    }
}
