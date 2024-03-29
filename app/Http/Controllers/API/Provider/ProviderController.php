<?php

namespace App\Http\Controllers\API\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductDeactivationRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductProviderResource;
use App\Models\Product;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductProviderResource::collection(Product::where('user_id', auth()->user()->id)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $product = Product::create(array_merge($request->validated(),
            ['user_id' => auth()->user()->id]));
        return new ProductProviderResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if ($product->user_id != auth()->user()->id){
            return response()->json([
                'error' => ['code' => '403','message' => 'Forbidden for you']], 403);
        }
        return new ProductProviderResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        if ($product->user_id != auth()->user()->id){
            return response()->json([
                'error' => ['code' => '403','message' => 'Forbidden for you']], 403);
        }
        $product->update($request->validated());
        return new ProductProviderResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->user_id != auth()->user()->id){
            return response()->json([
                'error' => ['code' => '403','message' => 'Forbidden for you']], 403);
        }
        $product->delete();
        return new ProductProviderResource($product);
    }

}
