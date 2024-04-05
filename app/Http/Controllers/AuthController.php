<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validate->fails()) {
            return $this->errorResponse('422',$validate->messages());
        }
        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $token = $user->createToken('myApp')->plainTextToken;
        return $this->successResponse(201,[
            'user' => $user,
            'token' => $token,
        ],'user created successfully');
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);
        if ($validate->fails()) {
            return $this->errorResponse('422',$validate->messages());
        }
        $user = User::query()->where('email',$request->email)->first();
        if (!Hash::check($request->password,$user->password)) {
            return $this->errorResponse('422','password is incorrect');
        }
        $token = $user->createToken('myApp')->plainTextToken;
        return $this->successResponse(200,[
            'user' => $user,
            'token' => $token,
        ],'user login ok');
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->successResponse('200','logged out');
    }
}
