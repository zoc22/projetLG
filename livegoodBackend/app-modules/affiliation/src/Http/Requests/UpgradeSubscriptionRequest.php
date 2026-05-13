<?php

namespace Modules\Affiliation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpgradeSubscriptionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'plan' => 'required|in:monthly,annual',
        ];
    }
}
