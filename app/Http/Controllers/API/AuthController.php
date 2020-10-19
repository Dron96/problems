<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Auth;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Регистрация пользователя
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        User::create($data);

        return response()->json(['message' => 'Вы успешно зарегистрированы'], 201);
    }

    /**
     * Авторизация пользователя
     *
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($request->validated())) {
            return response()->json([
                'errors' => 'Адрес электронной почты или пароль неправильные',
            ], 401);
        }
        $token = Auth::user()->createToken('authToken');

        return response()->json([
            'user' => auth()->user(),
            'access_token' => $token->accessToken,
        ], 200);
    }

    /**
     * Выход пользователя из аккаунта
     *
     *
     * @return JsonResponse
     */
    public function logout()
    {
        Auth::user()->token()->revoke();

        return response()->json([
            'message' => 'Вы успешно вышли',
        ], 200);
    }

    /**
     * Проверка является ли пользователь начальником подразделения
     *
     * @return JsonResponse
     */
    public function isGroupLeader()
    {
        $user = auth()->guard('api')->user();
        $group = $user->group;
        if (empty($group)) {
            return response()->json(['error' => 'Пользователь не состоит в подразделении'], 422);
        }

        return response()->json($group->leader_id === $user->id);
    }
}
