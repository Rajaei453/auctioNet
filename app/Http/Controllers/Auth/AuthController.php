<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\registerRequest;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthController extends Controller
{


    public function userRegister(registerRequest $request)
    {
      //  $user = Auth();//
        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'city' => $request['city'],
        ]);
        return redirect()->back()->with(['success' => 'You are registered ']);
    }
    public function providerRegister(registerRequest $request)
    {

        Provider::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'city' => $request['city'],
            'luxury' => $request['luxury'],
        ]);
        return redirect()->back()->with(['success' => 'You are registered *Please Log In*']);
    }

    public function login(Request $request)
    {

        $credentials = $request->only(['email', 'password']);

       if($token = auth('user')->attempt($credentials)){

           $user = auth('user')->user();

           $user->jwt_token = $token;

          // return $token;

           return redirect(route('showProviders',$token),302, []);
           }

       elseif($token = auth()->guard('provider')->attempt($credentials)){

             $provider = auth()->guard('provider')->user();

             $provider->jwt_token = $token;

            // return $token;

             return redirect(route('showUsers',$token),302, []);
           }
       else
           return redirect()->back()->with(['message' => 'wrong credentials ']);


    }
    public function logout($jwt_token)
    {
        try {
            $user = auth()->userOrFail();
        } catch (TokenExpiredException $e) {
            return redirect()->route('loginPage');

        } catch (JWTException $e) {
            return redirect()->route('loginPage');
        }
        if(!$jwt_token) return redirect()->route('loginPage');
        auth()->logout();

        return redirect()->route('login');
    }
}
