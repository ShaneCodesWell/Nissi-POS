<?php
namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class AdjustInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'     => ['required', 'string', 'in:increment,decrement'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason'   => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.in'         => 'Adjustment type must be either increment or decrement.',
            'quantity.min'    => 'Quantity must be at least 1.',
            'reason.required' => 'A reason is required for all manual stock adjustments.',
        ];
    }
}
