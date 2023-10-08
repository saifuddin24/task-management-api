<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\RolePermissionController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\UserPermissionController;
use App\Http\Controllers\Auth\UserRoleController;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskActivity;
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

Route::bind('trashed_project', function (string $value) {
    return Project::onlyTrashed()->findOrFail($value);
});

Route::bind('unfinished_activity', function (string $value) {
    return TaskActivity::query()->whereNull('finished_at')->findOrFail($value);
});

Route::bind('trashed_task', function (string $value) {
    return Task::onlyTrashed()->findOrFail($value);
});

Route::bind('activity', function (string $value) {
    return TaskActivity::query()->findOrFail($value);
});


Route::get('/init-app', [AppDataController::class,'index']);

Route::prefix('/users')->group(function(){
    Route::controller(UserController::class)->group(function(){
        Route::post('/login','login');
        Route::post('/logout','logout');

        Route::middleware('auth:sanctum')->group(function(){
            Route::get('/profile','profile');
            Route::post('/profile','update_profile');
        });

    });
});
Route::apiResource('/users', UserController::class)
    ->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function (){

    Route::apiResource('/roles',  RoleController::class)->except('update');
    Route::prefix('roles')->group(function(){

        Route::bind('permissions', function (string $value) {
            return Permission::query()
                ->whereIn('id', explode(',', $value))
                ->pluck('id')
                ->all( );
        });

        Route::controller(RoleController::class)->group(function(){
           Route::put( '/{role}/permission/{permissions}', 'permissions' );
        });

    });

    Route::apiResource('role-permissions', RolePermissionController::class)->except('update', 'show');

    Route::apiResource('permissions', PermissionController::class)->except('update');

    Route::apiResource('user-permissions', UserPermissionController::class)->except('update', 'show');

    Route::apiResource('user-roles', UserRoleController::class)->except('update', 'show');

    Route::apiResource('/projects', ProjectController::class);
    Route::prefix('/projects')->group(function(){
        Route::controller(ProjectController::class)->group( function (){
            Route::put('/{trashed_project}/restore', 'restore');
        });
    });

    Route::apiResource('/tasks', TaskController::class );
    Route::prefix('/tasks')->group(function(){
        Route::controller(TaskController::class)->group( function (){
            Route::put('/{trashed_task}/restore', 'restore');
        });
    });

    Route::prefix('tasks')->group(function(){
        Route::apiResource('/{task}/activities', TaskActivityController::class);
        Route::patch('/{task}/assign', [TaskController::class,'assign']);

        Route::controller(TaskActivityController::class)->group( function (){
            Route::put('/{task}/activities/{unfinished_activity}/finish', 'finish');
        });

    });

    Route::apiResource('teams', TeamController::class);

    Route::apiResource('team-projects', TeamProjectController::class);

    Route::apiResource('team-users', TeamUserController::class)->except('update', 'show');

});
