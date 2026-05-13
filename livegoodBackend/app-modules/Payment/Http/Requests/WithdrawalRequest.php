<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawalRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:20',
            'payment_method_id' => 'required|uuid|exists:payment_methods,id'
        ];
    }
}
