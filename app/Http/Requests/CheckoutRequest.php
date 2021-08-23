<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{

    public function prepareForValidation()
    {
        $this->merge([
            'products' => collect($this->products)
                ->filter(fn($item) => (isset($item['name']) &&
                    isset($item['price']) &&
                    is_numeric($item['price'])))
                ->toArray(),
            'orderItems' => collect($this->orderItems)
                ->filter(fn($item) => (isset($item['product'])))
                ->toArray(),
            'rules' => collect($this->rules)
                ->filter(fn($rule) => (isset($rule['product']) &&
                    isset($rule['quantities']) &&
                    is_numeric($rule['quantities']) &&
                    isset($rule['special_price']) &&
                    is_numeric($rule['special_price'])))
                ->toArray()
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'products' => ['required', 'array'],
            'rules' => ['required', 'array'],
            "orderItems" => ['required', 'array']
        ];
    }
}
