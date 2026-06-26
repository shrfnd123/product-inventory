<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('id');

        return [
            'name' => ['sometimes', 'string', 'max:255'],

            'sku' => [
                'sometimes',
                'string',
                'max:100',
                'unique:products,sku,' . $productId
            ],

            'description' => ['sometimes', 'nullable', 'string'],

            'price' => ['sometimes', 'numeric', 'min:0'],

            'stock' => ['sometimes', 'integer', 'min:0'],
        ];
    }
    public function messages(): array
    {
        return [
            'sku.unique' => 'This SKU is already taken by another product.',
            'price.numeric' => 'Price must be a valid number.',
        ];
    }
}
