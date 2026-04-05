<?php
namespace App\Http\Requests\Terminal;

use Illuminate\Foundation\Http\FormRequest;

class ApplyDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
        ];
    }

    // Normalise the code to uppercase before validation runs
    // so "save20", "SAVE20", "Save20" all resolve identically
    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim($this->code ?? '')),
        ]);
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Please enter a discount code.',
            'code.max'      => 'Discount codes cannot exceed 50 characters.',
        ];
    }
}
