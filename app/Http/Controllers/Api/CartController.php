<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CartLimitException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddProductCartRequest;
use App\Http\Requests\Cart\ListCartRequest;
use App\Http\Requests\Cart\RemoveProductCartRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\CartProductAddedResource;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    /**
     * Display a listing of the cart.
     *
     * @return \App\Http\Resources\CartResource
     */
    public function list(ListCartRequest $request): CartResource
    {
        $cart = Cart::getByReference($request->route('reference'));

        return new CartResource($cart);
    }

    /**
     * Store a newly created cart in storage.
     *
     * @return \App\Http\Resources\CartResource
     */
    public function create(): CartResource
    {
        $cart = new Cart();
        $cart->save();

        return new CartResource($cart);
    }

    /**
     * Add product to cart.
     *
     * @param  \App\Models\Product  $product
     * @return \App\Http\Resources\CartResource
     */
    public function addProduct(AddProductCartRequest $request): CartResource|JsonResponse
    {
        $cart = Cart::getByReference($request->route('reference'));
        try {
            $cart->addProduct($request->route('product_id'));
        } catch (CartLimitException $e) {
            return response()->apiValidationError($e->getMessage());
        }

        return new CartResource($cart);
    }

    /**
     * Remove product from cart.
     *
     * @param  int $productId
     * @return \App\Http\Resources\CartResource
     */
    public function removeProduct(RemoveProductCartRequest $request): CartResource
    {
        $cart = Cart::getByReference($request->route('reference'));
        $cart->removeProduct($request->route('product_id'));

        return new CartResource($cart);
    }
}
