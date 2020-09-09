<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'API\AuthController@register')
    ->name('register');
Route::post('/login', 'API\AuthController@login')
    ->name('login');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'API\AuthController@logout');

    Route::get('/users', 'API\UserController@getUsers');

    Route::prefix('problem')->group(function () {
        Route::post('/', 'API\ProblemController@store');
        Route::get('/', 'API\ProblemController@index');
        Route::delete('/{problem}', 'API\ProblemController@destroy');
        Route::put('/{problem}', 'API\ProblemController@update');
        Route::get('/{problem}', 'API\ProblemController@show');

        Route::get('/{problem}/solution', 'API\SolutionController@index');
        Route::post('/{problem}/solution', 'API\SolutionController@store');
        Route::get('/{problem}/solution-in-work', 'API\SolutionController@showInWork');
    });

    Route::prefix('solution')->group(function () {
        Route::delete('/{solution}', 'API\SolutionController@destroy');
        Route::put('/{solution}', 'API\SolutionController@update');
        Route::get('/{solution}', 'API\SolutionController@show');
        Route::put('/{solution}/change-in-work', 'API\SolutionController@changeInWork');
        Route::put('/{solution}/change-status', 'API\SolutionController@changeStatus');
        Route::put('/{solution}/set-deadline', 'API\SolutionController@setDeadline');
        Route::put('/{solution}/set-executor', 'API\SolutionController@setExecutor');
    });
});


