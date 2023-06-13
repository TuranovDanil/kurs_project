<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\SelectedResource;
use App\Models\Selected;

class SelectedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SelectedResource::collection(Selected::where('client_id', auth()->user()->id)->get());
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
        return new SelectedResource($selected);
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
        return new SelectedResource($selected);
    }
}
