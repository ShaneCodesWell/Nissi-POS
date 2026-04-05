<?php
namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $organization = $this->route('organization');
        $location     = $this->route('location');

        return [
            'name'      => ['required', 'string', 'max:255'],
            'address'   => ['sometimes', 'nullable', 'string', 'max:255'],
            'city'      => ['sometimes', 'nullable', 'string', 'max:100'],
            'phone'     => ['sometimes', 'nullable', 'string', 'max:30'],
            'email'     => ['sometimes', 'nullable', 'email', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],

            // Location code must be unique within the organization
            'code'      => [
                'required',
                'string',
                'max:20',
                Rule::unique('locations')
                    ->where('organization_id', $organization->id)
                    ->ignore($location?->id),
            ],
        ];
    }

    // Normalise the code to uppercase before validation
    protected function prepareForValidation(): void
    {
        if ($this->filled('code')) {
            $this->merge(['code' => strtoupper(trim($this->code))]);
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Location name is required.',
            'code.required' => 'A location code is required.',
            'code.unique'   => 'This location code is already in use at another branch.',
            'code.max'      => 'Location code cannot exceed 20 characters.',
            'email.email'   => 'Please enter a valid email address.',
        ];
    }
}
