<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request){
        $user = User::create($request->validated());
        $token = $user->createToken('auth_token');

        return new JsonResponse([
            'data'  =>  ['token' => $token->plainTextToken, 'user' => $user],
            'msg'   =>  'Registered successfully'
        ]);
    }

    public function login(UserLoginRequest $request){
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return new JsonResponse(['msg' => 'User is not found'], 404);
        }

        if(! Hash::check($request->password, $user->password)){
            return new JsonResponse(['msg' => 'Password is wrong'], 422);
        }
        $token = $user->createToken('auth_token');

        return new JsonResponse([
            'data'  =>  ['token' => $token->plainTextToken, 'user' => $user],
            'msg'   =>  'Logged in successfully'
        ]);
    }

    public function logout(Request $request){
        if(auth('sanctum')->user()) {
            auth('sanctum')->user()->tokens()->delete();
            return new JsonResponse(['msg' => 'Logged out successfully']);
        } else {
            return new JsonResponse(['msg' => 'You can not logged out, you are not logged in'], 422);
        }
    }
}
