<?php

namespace Modules\Affiliation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAffiliateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'username_canonical' => 'sometimes|string|min:3|unique:affiliates,username_canonical,' . $this->route('affiliate'),
        ];
    }
}
