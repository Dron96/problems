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
Route::post('/logout', 'API\AuthController@logout')
    ->name('logout')
    ->middleware('auth:api');

Route::post('/problem', 'API\ProblemController@store')
    ->name('problem.store')
    ->middleware('auth:api');
Route::get('/problem', 'API\ProblemController@index')
    ->name('problem.index')
    ->middleware('auth:api');
Route::delete('/problem/{problem}', 'API\ProblemController@destroy')
    ->name('problem.destroy')
    ->middleware('auth:api');
Route::put('/problem/{problem}', 'API\ProblemController@update')
    ->name('problem.update')
    ->middleware('auth:api');
Route::get('/problem/{problem}', 'API\ProblemController@show')
    ->name('problem.show')
    ->middleware('auth:api');



Route::post('/solution/{problem}', 'API\SolutionController@store')
    ->name('solution.store')
    ->middleware('auth:api');
Route::get('/solutions/{problem}', 'API\SolutionController@index')
    ->name('solution.index')
    ->middleware('auth:api');
Route::get('/solutions-in-work/{problem}', 'API\SolutionController@showInWork')
    ->name('solution.in-work')
    ->middleware('auth:api');
Route::delete('/solution/{solution}', 'API\SolutionController@destroy')
    ->name('solution.destroy')
    ->middleware('auth:api');
Route::put('/solution/{solution}', 'API\SolutionController@update')
    ->name('solution.update')
    ->middleware('auth:api');
Route::get('/solution/{solution}', 'API\SolutionController@show')
    ->name('solution.show')
    ->middleware('auth:api');
Route::put('/solution/{solution}/change-in-work', 'API\SolutionController@changeInWork')
    ->name('solution.changeInWork')
    ->middleware('auth:api');
