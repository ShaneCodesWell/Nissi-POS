<?php
namespace App\Http\Requests\Terminal;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Split payment route sends an array of payment objects
        if ($this->routeIs('*.payments.split')) {
            return $this->splitRules();
        }

        // Single payment routes (cash, card, mobile-money)
        return $this->singlePaymentRules();
    }

    // -------------------------------------------------------------------------
    // Single payment rules
    // -------------------------------------------------------------------------

    protected function singlePaymentRules(): array
    {
        $rules = [
            'amount' => ['required', 'numeric', 'min:0.01'],
        ];

        // Card and MoMo can optionally carry provider and reference info
        if ($this->routeIs('*.payments.card') || $this->routeIs('*.payments.mobile-money')) {
            $rules['provider']       = ['sometimes', 'nullable', 'string', 'max:100'];
            $rules['reference']      = ['sometimes', 'nullable', 'string', 'max:255'];
            $rules['account_number'] = ['sometimes', 'nullable', 'string', 'max:50'];
        }

        // Cash just needs the tendered amount — change is calculated automatically
        if ($this->routeIs('*.payments.cash')) {
            $rules['amount'] = ['required', 'numeric', 'min:0.01'];
        }

        return $rules;
    }

    // -------------------------------------------------------------------------
    // Split payment rules
    // -------------------------------------------------------------------------

    protected function splitRules(): array
    {
        return [
            'payments'                  => ['required', 'array', 'min:2'],
            'payments.*.method'         => ['required', 'string', new Enum(PaymentMethod::class)],
            'payments.*.amount'         => ['required', 'numeric', 'min:0.01'],
            'payments.*.provider'       => ['sometimes', 'nullable', 'string', 'max:100'],
            'payments.*.reference'      => ['sometimes', 'nullable', 'string', 'max:255'],
            'payments.*.account_number' => ['sometimes', 'nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required'            => 'A payment amount is required.',
            'amount.min'                 => 'Payment amount must be greater than zero.',
            'payments.min'               => 'A split payment requires at least two payment methods.',
            'payments.*.method.required' => 'Each split payment must specify a method.',
            'payments.*.method'          => 'Invalid payment method. Accepted: cash, card, mobile_money.',
            'payments.*.amount.min'      => 'Each payment amount must be greater than zero.',
        ];
    }
}
