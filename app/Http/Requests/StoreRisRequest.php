<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entity_name' => ['required', 'string', 'max:150'],
            'fund_cluster' => ['nullable', 'string', 'max:50'],
            'division_id' => ['required', 'exists:divisions,id'],
            'office' => ['required', 'string', 'max:150'],
            'responsibility_center_code' => ['nullable', 'string', 'max:100'],
            'purpose' => ['required', 'string'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.item_id' => ['required', 'exists:items,id'],
            'details.*.qty_requested' => ['required', 'numeric', 'min:0.01'],
            'details.*.remarks' => ['nullable', 'string'],
        ];
    }
}
