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
    public function show($id)
    {
        $product = Product::findOrFail($id);
        if ($product->deactivation == 1){
            return response()->json([
                'error' => ['code' => '403','message' => 'Forbidden for you']], 403);
        }
        return new ProductClientResourse($product);
    }

    public function select(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($product->deactivation == 1){
            return response()->json([
                'error' => ['code' => '403','message' => 'Forbidden for you']], 403);
        }
//
//        if(Selected::where('id_product', $id)->orWhere('id_client', auth()->user()->id) !== null){
//            return response()->json([
//                'error' => ['code' => '422','message' => 'Already selected']], 422);
//        }
        $selected = Selected::create(array_merge(
            ['product_id' => $id, 'client_id' => auth()->user()->id]));
        return new SelectedResourse($selected);
    }

}
