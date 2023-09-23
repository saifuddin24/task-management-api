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

Route::prefix('users')->group(function(){
    Route::apiResource('/', UserController::class)
        ->middleware('auth:sanctum');

    Route::controller(UserController::class)->group(function(){

        Route::post('/login','login');

        Route::middleware('auth:sanctum')->group(function(){
            Route::get('/profile','profile');
            Route::post('/profile','update_profile');
        });

    });

});

Route::middleware('auth:sanctum')->group(function (){


    Route::prefix('roles')->group(function(){

        Route::bind('permissions', function (string $value) {
            return Permission::query()
                ->whereIn('id', explode(',', $value))
                ->pluck('id')
                ->all( );
        });

        Route::apiResource('/',  RoleController::class)->except('update');

        Route::controller(RoleController::class)->group(function(){
           Route::put( '/{role}/permission/{permissions}', 'permissions' );
        });
    });

    Route::apiResource('role-permissions', RolePermissionController::class)->except('update', 'show');

    Route::apiResource('permissions', PermissionController::class)->except('update');

    Route::apiResource('user-permissions', UserPermissionController::class)->except('update', 'show');

    Route::apiResource('user-roles', UserRoleController::class)->except('update', 'show');

    Route::bind('trashed_project', function (string $value) {
        return Project::onlyTrashed()->findOrFail($value);
    });

    Route::prefix('projects')->group(function(){
        Route::apiResource('/', ProjectController::class);
        Route::controller(ProjectController::class)->scopeBindings()->group( function (){
            Route::put('/{trashed_project}/restore', 'restore');
        });
    });

    Route::apiResource('tasks', TaskController::class);

    Route::apiResource('task-activities', TaskActivityController::class);

    Route::apiResource('teams', TeamController::class);

    Route::apiResource('team-projects', TeamProjectController::class);

    Route::apiResource('team-users', TeamUserController::class)->except('update', 'show');

});
