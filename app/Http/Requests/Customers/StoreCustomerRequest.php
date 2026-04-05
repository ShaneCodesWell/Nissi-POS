<?php
namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $organization = $this->route('organization');
        $customer     = $this->route('customer');

        return [
            'first_name'       => ['required', 'string', 'max:100'],
            'last_name'        => ['sometimes', 'nullable', 'string', 'max:100'],
            'date_of_birth'    => ['sometimes', 'nullable', 'date', 'before:today'],
            'gender'           => ['sometimes', 'nullable', 'string', 'in:male,female,other,prefer_not_to_say'],
            'address'          => ['sometimes', 'nullable', 'string', 'max:255'],
            'city'             => ['sometimes', 'nullable', 'string', 'max:100'],
            'marketing_opt_in' => ['sometimes', 'boolean'],
            'notes'            => ['sometimes', 'nullable', 'string', 'max:1000'],

            // Email and phone must be unique within the organization,
            // but we ignore the current customer record on updates
            'email'            => [
                'sometimes',
                'nullable',
                'email',
                Rule::unique('customers')
                    ->where('organization_id', $organization->id)
                    ->ignore($customer?->id),
            ],
            'phone'            => [
                'sometimes',
                'nullable',
                'string',
                'max:30',
                Rule::unique('customers')
                    ->where('organization_id', $organization->id)
                    ->ignore($customer?->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'  => 'Customer first name is required.',
            'email.email'          => 'Please enter a valid email address.',
            'email.unique'         => 'A customer with this email already exists.',
            'phone.unique'         => 'A customer with this phone number already exists.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'gender.in'            => 'Invalid gender value.',
        ];
    }
}
