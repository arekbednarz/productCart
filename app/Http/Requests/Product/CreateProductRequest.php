<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\ApiRequest;

class CreateProductRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => [
                'required',
                'unique:products',
            ],
            'price' => [
                'required',
                'numeric',
                'regex:/^(([0-9]*)(\.([0-9]{0,2}+))?)$/',
            ],
            'currency' => [
                'required',
                'exists:currencies,symbol',
            ],
        ];
    }


}
