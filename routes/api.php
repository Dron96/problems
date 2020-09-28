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
        Route::post('/{problem}/like', 'API\ProblemController@likeProblem');
        Route::post('/{problem}/send-to-group', 'API\ProblemController@sendToGroup');
        Route::put('/{problem}/set-experience', 'API\ProblemController@setExperience');
        Route::put('/{problem}/set-result', 'API\ProblemController@setResult');
        Route::put('/{problem}/set-possible-solution', 'API\ProblemController@setPossibleSolution');
        Route::put('/{problem}/set-description', 'API\ProblemController@setDescription');
        Route::put('/{problem}/set-importance', 'API\ProblemController@setImportance');
        Route::put('/{problem}/set-progress', 'API\ProblemController@setProgress');
        Route::put('/{problem}/set-urgency', 'API\ProblemController@setUrgency');
        Route::put('/{problem}/send-for-confirmation', 'API\ProblemController@sendForConfirmation');
        Route::put('/{problem}/reject-solution', 'API\ProblemController@rejectSolution');
        Route::put('/{problem}/confirm-solution', 'API\ProblemController@confirmSolution');

        Route::get('/{problem}/solution', 'API\SolutionController@index');
        Route::post('/{problem}/solution', 'API\SolutionController@store');
    });

    Route::prefix('solution/{solution}')->group(function () {
        Route::delete('/', 'API\SolutionController@destroy');
        Route::put('/', 'API\SolutionController@update');
        Route::get('/', 'API\SolutionController@show');
        Route::put('/set-plan', 'API\SolutionController@setPlan');
        Route::put('/set-team', 'API\SolutionController@setTeam');
        Route::put('/change-status', 'API\SolutionController@changeStatus');
        Route::put('/set-deadline', 'API\SolutionController@setDeadline');
        Route::put('/set-executor', 'API\SolutionController@setExecutor');

        Route::post('/task', 'API\TaskController@store');
        Route::get('/task', 'API\TaskController@index');
    });

    Route::prefix('task/{task}')->group(function () {
        Route::put('/', 'API\TaskController@update');
        Route::delete('/', 'API\TaskController@destroy');
        Route::put('/set-executor', 'API\TaskController@setExecutor');
        Route::put('/set-deadline', 'API\TaskController@setDeadline');
        Route::put('/change-status', 'API\TaskController@changeStatus');
        Route::get('/', 'API\TaskController@show');
    });

    Route::prefix('group')->group(function () {
        Route::get('/', 'API\GroupController@index');
        Route::get('/{group}/leader', 'API\GroupController@getLeader');
        Route::get('/{group}/user', 'API\GroupController@getUsers');
        Route::get('/{group}', 'API\GroupController@show');
        Route::post('/', 'API\GroupController@store');
        Route::put('{group}/user/{user}', 'API\GroupController@addUser');
        Route::put('{group}', 'API\GroupController@update');
        Route::put('{group}/change-short-name', 'API\GroupController@updateShortName');
        Route::put('{group}/remove-user/{user}', 'API\GroupController@removeUserFromGroup');
        Route::put('{group}/change-leader/{user}', 'API\GroupController@changeLeader');
        Route::delete('{group}', 'API\GroupController@destroy');
    });
});


