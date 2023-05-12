<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResourse;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return CategoryResourse::collection(Category::with('products')->get());
    }

    public function show(string $id)
    {
        return new CategoryResourse(Category::with('products')->findOrFail($id));
    }
}
