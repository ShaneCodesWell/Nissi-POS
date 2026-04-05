<?php
namespace App\Http\Requests\Customers;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class RedeemPointsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'points'  => ['required', 'integer', 'min:1'],
            'sale_id' => ['required', 'integer', 'exists:sales,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $customer = $this->route('customer');

            // Verify the customer has enough points before it reaches the service
            if ($this->points && $customer instanceof Customer) {
                if ($customer->points_balance < $this->points) {
                    $validator->errors()->add(
                        'points',
                        "Insufficient points. Customer has {$customer->points_balance} points, "
                        . "but {$this->points} were requested."
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'points.required'  => 'Please specify how many points to redeem.',
            'points.min'       => 'Points to redeem must be at least 1.',
            'sale_id.required' => 'A sale must be selected for points redemption.',
            'sale_id.exists'   => 'The selected sale does not exist.',
        ];
    }
}
