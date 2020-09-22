<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use App\Http\Requests\Register;
use App\User;
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

        return response()->json([
            'user' => auth()->user(),
            'access_token' => $token->accessToken,
        ], 200);
    }

    /**
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
