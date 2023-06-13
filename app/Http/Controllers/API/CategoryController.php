<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Resources\CategoryResourse;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return CategoryResourse::collection(Category::all());
    }

    public function show(Category $category)
    {
        return new CategoryResourse($category);
    }

    public function store(CategoryStoreRequest $request){
        $category = Category::create($request->validated());
        return new CategoryResourse($category);
    }

    public function update(CategoryStoreRequest $request, Category $category)
    {
        $category->update($request->validated());
        return new CategoryResourse($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return new CategoryResourse($category);
    }
}
