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
Route::get('/is-group-leader', 'API\AuthController@isGroupLeader');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'API\AuthController@logout');

    Route::get('/users', 'API\UserController@getUsers');

    Route::get('solution/{solution}/potential-executors',
        'API\SolutionController@getPotentialExecutors');

    Route::prefix('problem')->group(function () {
        Route::get('/count-problems', 'API\ProblemController@countProblems');

        Route::post('/', 'API\ProblemController@store');
        Route::get('/', 'API\ProblemController@index');
        Route::get('/my-problems', 'API\ProblemController@userProblems');
        Route::get('/group-problems', 'API\ProblemController@problemsForConfirmation');
        Route::get('/problems-for-execution', 'API\ProblemController@problemsForExecution');
        Route::get('/problems-by-groups/{group}', 'API\ProblemController@problemsByGroups');
        Route::get('/problems-of-all-groups', 'API\ProblemController@problemsOfAllGroups');
        Route::get('/problems-archive', 'API\ProblemController@problemsArchive');
        Route::get('/problems-user-archive', 'API\ProblemController@problemsUserArchive');
        Route::get('/problems-group-archive', 'API\ProblemController@problemsGroupArchive');
        Route::get('/statistic/quantitative-indicators', 'API\ProblemController@statisticQuantitativeIndicators');
        Route::get('/statistic/categories', 'API\ProblemController@statisticCategories');
        Route::get('/statistic/quarterly', 'API\ProblemController@statisticQuarterly');

        Route::get('/{problem}', 'API\ProblemController@show');
        Route::post('/{problem}/like', 'API\ProblemController@likeProblem');

        Route::middleware('can:changeUrgencyImportanceProgress,problem')->group(function () {
            Route::put('/{problem}/set-importance', 'API\ProblemController@setImportance');
            Route::put('/{problem}/set-progress', 'API\ProblemController@setProgress');
            Route::put('/{problem}/set-urgency', 'API\ProblemController@setUrgency');
        });

        Route::middleware('can:changeExperienceResultSendForConfirmationSendToGroup,problem')
            ->group(function () {
                Route::put('/{problem}/set-experience', 'API\ProblemController@setExperience');
                Route::put('/{problem}/set-result', 'API\ProblemController@setResult');
                Route::post('/{problem}/send-to-group', 'API\ProblemController@sendToGroup');
                Route::put('/{problem}/send-for-confirmation', 'API\ProblemController@sendForConfirmation');
            });

        Route::middleware('can:changeOwnProblem,problem')
            ->group(function () {
                Route::put('/{problem}/reject-solution', 'API\ProblemController@rejectSolution');
                Route::put('/{problem}/confirm-solution', 'API\ProblemController@confirmSolution');
                Route::delete('/{problem}', 'API\ProblemController@destroy');
            });

        Route::middleware('can:changeOwnModeratingProblem,problem')
            ->group(function () {
                Route::put('/{problem}/set-possible-solution', 'API\ProblemController@setPossibleSolution');
                Route::put('/{problem}/set-description', 'API\ProblemController@setDescription');
                Route::put('/{problem}', 'API\ProblemController@update');
            });

        Route::get('/{problem}/solution', 'API\SolutionController@index');
    });

    Route::prefix('solution/{solution}')->group(function () {
        Route::get('/', 'API\SolutionController@show');

        Route::put('/', 'API\SolutionController@update')
            ->middleware('can:changeName,solution');

        Route::middleware('can:changePlanTeamStatusDeadline,solution')->group(function () {
            Route::put('/set-plan', 'API\SolutionController@setPlan');
            Route::put('/add-user-to-team/{user}', 'API\SolutionController@addUserToTeam');
            Route::put('/remove-user-from-team/{user}', 'API\SolutionController@removeUserFromTeam');
            Route::put('/change-status', 'API\SolutionController@changeStatus');
            Route::put('/set-deadline', 'API\SolutionController@setDeadline');
        });

        Route::put('/set-executor', 'API\SolutionController@setExecutor')
            ->middleware('can:changeExecutor');

        Route::post('/task', 'API\TaskController@store')
            ->middleware('can:create,App\Task,solution');
        Route::get('/task', 'API\TaskController@index');
    });

    Route::prefix('task/{task}')->group(function () {
        Route::get('/', 'API\TaskController@show');

        Route::middleware('can:allFunctionExceptUpdateStatus,task')->group(function () {
            Route::put('/', 'API\TaskController@update');
            Route::delete('/', 'API\TaskController@destroy');
            Route::put('/set-executor', 'API\TaskController@setExecutor');
            Route::put('/set-deadline', 'API\TaskController@setDeadline');
        });

        Route::put('/change-status', 'API\TaskController@changeStatus')
            ->middleware('can:changeStatus,task');
    });

    Route::prefix('group')->group(function () {
        Route::get('/', 'API\GroupController@index');
        Route::get('/{group}/leader', 'API\GroupController@getLeader');
        Route::get('/{group}/user', 'API\GroupController@getUsers');
        Route::get('/{group}', 'API\GroupController@show');

        Route::middleware('can:adminFunctional,App\Group')->group(function () {
            Route::post('/', 'API\GroupController@store');
            Route::put('{group}/user/{user}', 'API\GroupController@addUser');
            Route::put('{group}', 'API\GroupController@update');
            Route::put('{group}/remove-user/{user}', 'API\GroupController@removeUserFromGroup');
            Route::put('{group}/change-leader/{user}', 'API\GroupController@changeLeader');
            Route::delete('{group}', 'API\GroupController@destroy');
        });
    });
});


