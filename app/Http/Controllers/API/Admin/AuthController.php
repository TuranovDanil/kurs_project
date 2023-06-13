<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRegisterRequest;
use App\Http\Requests\ProviderRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\ClientResource;
use App\Http\Resources\ProviderResource;
use App\Http\Resources\UserProductResource;
use App\Http\Resources\UserResource;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'registerProvider', 'registerClient']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['login', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function registerProvider(ProviderRegisterRequest $request) {
        $user = User::create(array_merge($request->validated(),
            ['password' => bcrypt($request->password), 'role' => 2]));
        return new ProviderResource($user);
    }

    public function registerClient(ClientRegisterRequest $request) {
        $user = User::create(array_merge($request->validated(),
            ['password' => bcrypt($request->password), 'role' => 3]));
        return new ClientResource($user);
    }


    /**
     * Get the authenticated User.
     *
     * @return UserResource
     */
    public function me()
    {
        return new UserResource(auth()->user());
    }

    public function update(UserUpdateRequest $request)
    {
        auth()->user()->update($request->validated());
        return new UserResource(auth()->user());
    }

    public function destroy(){
        auth()->user()->delete();
        return new UserResource(auth()->user());
    }



    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
