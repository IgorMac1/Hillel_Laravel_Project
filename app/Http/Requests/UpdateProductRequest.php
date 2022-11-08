<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $productId = $this->route('product')->id;

        return [
            'title' => ['required', 'string', 'min:3', Rule::unique('products', 'title')->ignore($productId)],
            'description' => ['required', 'string', 'min:10'],
            'short_description' => ['required', 'string', 'min:10', 'max:150'],
            'SKU' => ['required', 'string', 'min:1', 'max:35', Rule::unique('products', 'SKU')->ignore($productId)],
            'price' => ['required', 'numeric', 'min:1'],
            'discount' => ['required', 'numeric', 'min:0', 'max:99'],
            'in_stock' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'numeric'],
            'thumbnail' => ['nullable', 'image:jpeg,png'],
            'images.*' => ['image:jpeg,png'],
        ];
    }
}
