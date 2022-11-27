<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'reference' => $this->reference,
            'products' => CartItemResource::collection($this->cartItems()->get()),
            'total' => $this->getFixedTotalNumberFormat(),
        ];
    }

    private function getFixedTotalNumberFormat() {
        $total = $this->getTotal();

        array_walk($total, function (&$value) {
            $value = number_format($value, 2);
        });

        return $total;
    }
}
