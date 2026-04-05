<?php
namespace App\Http\Requests\Discounts;

use App\Enums\DiscountScope;
use App\Enums\DiscountType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => ['required', 'string', 'max:255'],
            'description'          => ['sometimes', 'nullable', 'string', 'max:500'],
            'type'                 => ['required', new Enum(DiscountType::class)],
            'value'                => ['required', 'numeric', 'min:0.01'],
            'scope'                => ['required', new Enum(DiscountScope::class)],
            'minimum_order_amount' => ['sometimes', 'numeric', 'min:0'],
            'max_uses'             => ['sometimes', 'nullable', 'integer', 'min:1'],
            'is_active'            => ['sometimes', 'boolean'],
            'starts_at'            => ['sometimes', 'nullable', 'date'],
            'expires_at'           => ['sometimes', 'nullable', 'date', 'after:starts_at'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Percentage discounts cannot exceed 100%
            if (
                $this->type === DiscountType::Percentage->value &&
                $this->value > 100
            ) {
                $validator->errors()->add(
                    'value',
                    'Percentage discount cannot exceed 100%.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Discount name is required.',
            'type.required'    => 'Discount type is required (percentage or fixed).',
            'value.required'   => 'Discount value is required.',
            'value.min'        => 'Discount value must be greater than zero.',
            'scope.required'   => 'Discount scope is required (order or item).',
            'expires_at.after' => 'Expiry date must be after the start date.',
        ];
    }
}
