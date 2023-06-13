<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProviderRegisterRequest;
use App\Http\Resources\CategoryResourse;
use App\Http\Resources\UserResourse;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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
//        $validator = Validator::make($request->all(), [
//            'login' => 'required|string|max:255|unique:users',
//            'password' => 'required|string',
//        ]);
//        if($validator->fails()){
//            return response()->json($validator->errors()->toJson(), 400);
//        }
//        $user = User::create(array_merge(
//            $validator->validated(),
//            ['password' => bcrypt($request->password), 'role' => 2]
//        ));
//        return response()->json([
//            'message' => 'User successfully registered',
//            'user' => $user
//        ], 201);
        $user = User::create(array_merge($request->validated(),
            ['password' => bcrypt($request->password), 'role' => 2]));
        return new UserResourse($user);
    }

    public function registerClient(Request $request) {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255|unique:users',
            'password' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password), 'role' => 3]
        ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
//        return response()->json(auth()->user());
        return User::find(auth()->user());
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