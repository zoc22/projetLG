<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'amount'   => 'required|numeric|gt:0',
            'method'   => 'required|string',
            'currency' => 'required|string|size:3'
        ];
    }
}
