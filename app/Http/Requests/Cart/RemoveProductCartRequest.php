<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class RemoveProductCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'reference' => [
                'required',
                'exists:carts,reference'
            ],
            'product_id' => [
                'required',
            ]
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['reference'] = $this->route('reference');
        $data['product_id'] = $this->route('product_id');

        return $data;
    }
}
