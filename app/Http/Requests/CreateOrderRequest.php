<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'products' => ['required', 'array'],
            'products.*.product_id' => ['required', 'uuid', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1']
        ];
    }

    public function messages()
    {
        return [
            'products.*.product_id.exists' => 'Product not found or maybe deleted'
        ];
    }
}
