<?php
namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:100'],
            'parent_id'   => ['sometimes', 'nullable', 'integer', 'exists:categories,id'],
            'description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'image_path'  => ['sometimes', 'nullable', 'string', 'max:500'],
            'sort_order'  => ['sometimes', 'integer', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        // Prevent a category from being its own parent
        $validator->after(function ($validator) {
            $category = $this->route('category');

            if ($category && $this->parent_id == $category->id) {
                $validator->errors()->add(
                    'parent_id',
                    'A category cannot be its own parent.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Category name is required.',
            'parent_id.exists' => 'The selected parent category does not exist.',
        ];
    }
}
