<?php

namespace App\Http\Requests\Cart;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ListCartRequest extends ApiRequest
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
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['reference'] = $this->route('reference');

        return $data;
    }
}
