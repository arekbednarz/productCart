<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Responses\ApiResponse;
use App\Models\Currency;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::paginate(3);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Product\CreateProductRequest  $request
     * @return \App\Http\Resources\ProductResource
     */
    public function store(CreateProductRequest $request): ProductResource
    {
        $product = new Product();
        $product->title = $request->get('title');
        $product->price = $request->get('price');

        $currency = Currency::getBySymbol($request->get('currency'));
        $product->currency()->associate($currency);

        $product->save();

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \App\Http\Resources\ProductResource
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->all());
        if ($request->has('currency')) {
            $currency = Currency::getBySymbol($request->get('currency'));
            $product->currency()->associate($currency)->save();
        }

        return ApiResponse::success('Product updated successfully', $product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return ApiResponse::success('Product deleted successfully');
    }
}
