<?php

namespace App\Models;

use App\Exceptions\CartLimitException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    const MAX_CART_ITEMS = 3;
    const MAX_ITEM_QUANTITY = 10;

    public function cartItems() {
        return $this->hasMany(CartItem::class);
    }

    public static function getByReference(string $reference) {
        return self::query()->where('reference', $reference)->first();
    }

    public function addProduct(int $productId) {
        $cartItem = $this->cartItems()->firstOrNew([
            'product_id' => $productId
        ]);

        if (!$cartItem->exists && $this->cartItems()->count() >= static::MAX_CART_ITEMS) {
            throw new CartLimitException('You can\'t add more than ' . static::MAX_CART_ITEMS . ' products to the cart');
        }

        $cartItem->quantity++;

        if ($cartItem->quantity > 10) {
            throw new CartLimitException('You can\'t add more than ' . static::MAX_ITEM_QUANTITY . ' units of the same product to the cart');
        }

        $cartItem->save();
    }

    public function removeProduct(int $productid) {
        $cartItem = $this->cartItems()->where('product_id', $productid)->first();
        if ($cartItem) {
            if ($cartItem->quantity > 1) {
                $cartItem->quantity--;
                $cartItem->save();
            } else {
                $cartItem->delete();
            }
        }
    }

    public function getTotal() {
        $total = [];
        $cartItems = $this->cartItems()->get(['product_id', 'quantity']);
        foreach ($cartItems as $cartItem) {
            if (!isset($total[$cartItem->product->currency->symbol])) {
                $total[$cartItem->product->currency->symbol] = number_format(0, 2);
            }
            $productSumPrice = number_format($cartItem->product->price, 2) * $cartItem->quantity;
            $total[$cartItem->product->currency->symbol] = number_format($total[$cartItem->product->currency->symbol], 2) + $productSumPrice;
        }

        return $total;
    }

    public function save(array $options = [])
    {
        $this->hydrateCartWithReference();
        return parent::save($options);
    }

    private function hydrateCartWithReference() {
        if (!$this->exists) {
            $this->reference = Str::uuid()->toString();
        }
    }

}
