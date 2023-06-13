<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\CategoryResourse;
use App\Http\Resources\ProductClientResourse;
use App\Http\Resources\ProductProviderResourse;
use App\Http\Resources\SelectedResourse;
use App\Models\Product;
use App\Models\Selected;
use App\Models\User;
use Illuminate\Http\Request;

class ProductClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductClientResourse::collection(Product::where('deactivation', 0)->get());
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

    public function select(Product $product)
    {
        if ($product->deactivation == 1){
            return response()->json([
                'error' => ['code' => '403','message' => 'Forbidden for you']], 403);
        }
        $selected = Selected::create(array_merge(
            ['product_id' => $product->id, 'client_id' => auth()->user()->id]));
        return new SelectedResourse($selected);
    }

}
