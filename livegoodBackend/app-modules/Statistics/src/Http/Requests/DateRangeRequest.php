<?php

namespace Modules\Statistics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation pour les filtres de dates dans les rapports stats.
 */
class DateRangeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ];
    }
}
