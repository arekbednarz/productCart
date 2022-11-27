<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\ApiRequest;

class UpdateProductRequest extends ApiRequest
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
                'unique:products',
            ],
            'price' => [
                'numeric',
                'regex:/^(([0-9]*)(\.([0-9]{0,2}+))?)$/',
            ],
            'currency' => [
                'exists:currencies,symbol',
            ],
        ];
    }


}
