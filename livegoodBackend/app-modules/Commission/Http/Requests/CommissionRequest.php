<?php

namespace Modules\Commission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommissionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'period' => 'nullable|string|regex:/^\d{4}-(W\d{2}|M\d{2})$/',
            'status' => 'nullable|string|in:pending,paid,validated,cancelled',
        ];
    }
}
