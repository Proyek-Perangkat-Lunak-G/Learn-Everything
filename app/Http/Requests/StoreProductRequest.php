<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'price' => 'required|numeric|min:0|max:9999999999.99',
            'stock' => 'required|integer|min:0|max:2147483647',
            'type' => 'nullable|in:physical,digital,service',
        ];
    }

    public function messages(): array
    {
        return [
            'price.max' => 'Harga maksimum adalah 9.999.999.999,99.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'stock.max' => 'Stok maksimum adalah 2.147.483.647.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok tidak boleh negatif.',
        ];
    }
}
