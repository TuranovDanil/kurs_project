<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductClientResource;
use App\Http\Resources\SelectedResource;
use App\Models\Product;
use App\Models\Selected;

class ProductClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductClientResource::collection(Product::where('deactivation', 0)->get());
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if ($product->deactivation == 1){
            return response()->json([
                'error' => ['code' => '403','message' => 'Forbidden for you']], 403);
        }
        return new ProductClientResource($product);
    }

    public function select(Product $product)
    {
        if ($product->deactivation == 1){
            return response()->json([
                'error' => ['code' => '403','message' => 'Forbidden for you']], 403);
        }
        $selected = Selected::create(array_merge(
            ['product_id' => $product->id, 'client_id' => auth()->user()->id]));
        return new SelectedResource($selected);
    }

}
