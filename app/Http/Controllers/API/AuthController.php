<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use App\Http\Requests\Register;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @param Register $request
     * @return JsonResponse
     */
    public function register(Register $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        return response()->json(['message' => 'Вы успешно зарегистрированы'], 201);
    }

    /**
     * @param Login $request
     * @return JsonResponse
     */
    public function login(Login $request)
    {
        if(!auth()->attempt($request->validated())) {
            return response()->json([
                'errors' => 'Адрес электронной почты или пароль неправильные',
            ], 401);
        }
        $token = auth()->user()->createToken('authToken');
        //$token->token->expires_at = Carbon::now()->addDay();

        return response()->json([
            'user' => auth()->user(),
            'access_token' => $token->accessToken,
//            'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->user()->token()->revoke();

        return response()->json([
            'message' => 'Вы успешно вышли',
        ], 200);
    }
}
