<?php
namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'name'                  => ['required', 'string', 'max:255'],
            'category_id'           => ['sometimes', 'nullable', 'integer', 'exists:categories,id'],
            'description'           => ['sometimes', 'nullable', 'string'],
            'image_path'            => ['sometimes', 'nullable', 'string', 'max:500'],
            'is_active'             => ['sometimes', 'boolean'],

            // Variants — required on create, optional on update
            'variants'              => [$this->isMethod('POST') ? 'required' : 'sometimes', 'array', 'min:1'],
            'variants.*.name'       => ['sometimes', 'nullable', 'string', 'max:255'],
            'variants.*.sku'        => ['required_with:variants', 'string', 'max:100', 'distinct'],
            'variants.*.barcode'    => ['sometimes', 'nullable', 'string', 'max:100'],
            'variants.*.price'      => ['required_with:variants', 'numeric', 'min:0'],
            'variants.*.cost_price' => ['sometimes', 'numeric', 'min:0'],
            'variants.*.attributes' => ['sometimes', 'nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                  => 'Product name is required.',
            'variants.required'              => 'At least one variant is required.',
            'variants.*.sku.required_with'   => 'Each variant must have a SKU.',
            'variants.*.sku.distinct'        => 'Variant SKUs must be unique within this product.',
            'variants.*.price.required_with' => 'Each variant must have a price.',
            'variants.*.price.min'           => 'Variant price cannot be negative.',
            'variants.*.cost_price.min'      => 'Cost price cannot be negative.',
        ];
    }
}
