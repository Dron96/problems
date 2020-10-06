<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws Exception|Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param Throwable $exception
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
            switch ($exception->getModel()) {
                case 'App\Models\Problem':
                    return response()->json(['message' => 'Такой проблемы не существует'], 404);
                    break;
                case 'App\Models\Solution':
                    return response()->json(['message' => 'Такого решения не существует'], 404);
                    break;
                case 'App\Models\Task':
                    return response()->json(['message' => 'Такой задачи не существует'], 404);
                    break;
                case 'App\Models\Group':
                    return response()->json(['message' => 'Такого подразделения не существует'], 404);
                    break;
                case 'App\User':
                    return response()->json(['message' => 'Такого пользователя не существует'], 404);
                    break;
            }

        }
        if ($exception instanceof AuthorizationException) {
            return response()->json(['message' => 'У вас недостаточно прав'], 403);
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['errors' => 'Вы не авторизованы.'], 401);
    }
}
