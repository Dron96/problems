<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use App\Http\Requests\Register;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Register $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(['user' => $user, 'accessToken' => $accessToken], 201);
    }

    public function login(Login $request)
    {
        if(!auth()->attempt($request->validated())) {
            return response()->json([
                'message' => 'Имя пользователя или пароль неправильные',
                'errors' => 'Unauthorised'
            ], 401);
        }
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json([
            'user' => auth()->user(),
            'access_token' => $accessToken,
        ], 200);
    }
}
