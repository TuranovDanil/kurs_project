<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResourse;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return UserResourse::collection(User::with('products')->get());
    }

    public function show(string $id)
    {
        return new UserResourse(User::with('products')->findOrFail($id));
    }
}
