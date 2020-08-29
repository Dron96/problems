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

        return response()->json(['message' => 'Вы успешно зарегистрированы'], 201);
    }

    public function login(Login $request)
    {
        if(!auth()->attempt($request->validated())) {
            return response()->json([
                'message' => 'Имя пользователя или пароль неправильные',
                'errors' => 'Unauthorised'
            ], 401);
        }
        $token = auth()->user()->createToken('authToken');
        $token->token->expires_at = Carbon::now()->addDay();

        return response()->json([
            'user' => auth()->user(),
            'access_token' => $token->accessToken,
            'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
        ], 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();

        return response()->json([
            'message' => 'Вы успешно вышли',
        ], 200);
    }
}
