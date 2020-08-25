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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/problem', 'API\ProblemController@store')->name('problem.store');
Route::get('/problem', 'API\ProblemController@index')->name('problem.index');
Route::delete('/problem/{problem}', 'API\ProblemController@destroy')->name('problem.destroy');
Route::put('/problem/{problem}', 'API\ProblemController@update')->name('problem.update');
Route::get('/problem/{problem}', 'API\ProblemController@show')->name('problem.show');
