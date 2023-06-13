<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResourse;
use App\Http\Resources\ProductClientResourse;
use App\Http\Resources\ProductProviderResourse;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductSelectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SelectedResourse::collection(Product::where('user_id', auth()->user()->id)->get());
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
        return new ProductClientResourse($product);
    }

}
