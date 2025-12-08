<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:pending,processing,paid,completed,cancelled'],
            'payment_status' => ['nullable', 'in:pending,settlement,cancel,expire,failed,waiting'],
            'requires_followup' => ['sometimes', 'boolean'],
            'internal_notes' => ['nullable', 'string'],
        ];
    }
}
