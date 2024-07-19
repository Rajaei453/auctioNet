<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\registerRequest;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    protected function respondWithToken($token){

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 30,
            'user' => auth('api')->user()
        ]);
    }

    public function refresh() {
        return  $this->respondWithToken(auth()->refresh());
    }
    public function userRegister(Request  $request)
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        //  $user = Auth();//
        $user = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        if ($user)
        return response()->json(['success' => 'You are registered ']);
        else
            return response()->json(['fail' => 'You are already registered ']);
    }

    public function login(Request $request)
    {

        $credentials = $request->only(['email', 'password']);

        if($token = auth('user')->attempt($credentials)){

            $user = auth('user')->user();

            $user->jwt_token = $token;

            $user->type = 'user';

            // return $token;

            return response()->json(['details'=>$user]);
        }

        else
            return response()->json(['message' => 'wrong credentials ']);


    }
    public function details()
    {

        $user = auth('user')->user();

        return response()->json(['details'=>$user]);
    }
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'User logged out successfully']);
        } catch (\Exception $e) {
            // Something went wrong whilst attempting to log out
            return response()->json(['message' => 'Could not log out user']);
        }
    }
}
