<?php

namespace App\Http\Requests\Admin;

use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;

class ProductNewRequest extends FormRequest
{
    use ApiValidation;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' =>'required|exists:categories,id',
            'brand_id' =>'required|exists:brands,id',
            'name' =>'required|string',
            'slug' =>'required|unique:products',
            'image' =>'required|image|mimes:jpg,jpeg,svf,png',
            'desc' =>'required|string',
            'price' =>'required|integer',
            'quantity' =>'required|integer',
        ];
    }
}
