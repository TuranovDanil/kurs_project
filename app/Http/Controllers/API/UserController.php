<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\UserBannedRequest;
use App\Http\Resources\CategoryResourse;
use App\Http\Resources\ProviderResourse;
use App\Http\Resources\UserProductResourse;
use App\Http\Resources\UserResourse;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return UserProductResourse::collection(User::all());
    }

    public function show(User $user)
    {
        return new UserResourse($user);
    }

    public function store(AdminRegisterRequest $request){
        $user = User::create(array_merge($request->validated(),
            ['password' => bcrypt($request->password)]));
        return new ProviderResourse($user);
    }

    public function update(UserBannedRequest $request, User $user)
    {
        $user->update($request->validated());
        return new UserResourse($user);
    }
}
