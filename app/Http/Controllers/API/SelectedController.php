<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductProviderResourse;
use App\Http\Resources\SelectedResourse;
use App\Models\Product;
use App\Models\Selected;
use Illuminate\Http\Request;

class SelectedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SelectedResourse::collection(Selected::where('client_id', auth()->user()->id)->get());
    }

    /**
     * Display the specified resource.
     */
    public function show(Selected $selected)
    {
        if ($selected->client_id != auth()->user()->id){
            return response()->json([
                'error' => ['code' => '403','message' => 'Forbidden for you']], 403);
        }
        return new SelectedResourse($selected);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Selected $selected)
    {
        if ($selected->client_id != auth()->user()->id){
            return response()->json([
                'error' => ['code' => '403','message' => 'Forbidden for you']], 403);
        }
        $selected->delete();
        return new SelectedResourse($selected);
    }
}
