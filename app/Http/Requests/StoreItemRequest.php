<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage inventory');
    }

    public function rules(): array
    {
        return [
            'stock_no' => ['required', 'string', 'max:50', 'unique:items,stock_no'],
            'item_code' => ['required', 'string', 'max:50', 'unique:items,item_code'],
            'description' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:50'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'quantity_on_hand' => ['required', 'numeric', 'min:0'],
            'reorder_level' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,low_stock,out_of_stock,inactive'],
        ];
    }
}
