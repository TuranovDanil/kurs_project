<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\UserBannedRequest;
use App\Http\Resources\ProviderResource;
use App\Http\Resources\UserProductResource;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        return UserProductResource::collection(User::all());
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function store(AdminRegisterRequest $request){
        $user = User::create(array_merge($request->validated(),
            ['password' => bcrypt($request->password)]));
        return new ProviderResource($user);
    }

    public function update(UserBannedRequest $request, User $user)
    {
        $user->update($request->validated());
        return new UserResource($user);
    }
}
