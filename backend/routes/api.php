<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\InvitedUserController;
use App\Http\Controllers\API\v1\ProjectController;
use App\Http\Controllers\API\v1\TaskController;
use App\Http\Controllers\API\v1\TeamController;
use App\Http\Controllers\API\v1\TeamTaskController;


// Auth routes
Route::group([
    'middleware' => ['api'], // No need for 'auth:api' here
    'prefix' => 'v1/auth'
], function () {
    Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware('auth:api')->name('register');
    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('auth:api')->name('login');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->name('me');
});

// Google authentication route
Route::post('/v1/auth/google-auth', [AuthController::class, 'GoogleAuth']);

// Protected routes for projects, tasks and teams
Route::group([
    'middleware' => ['api', 'auth:api'], // Authentication required
    'prefix' => 'v1'
], function () {
    // Invitation routes
    Route::apiResource('invitations', InvitedUserController::class)->except(['update', 'show']);

    // Nested Project Routes
    Route::prefix('projects')->group(function () {
        Route::middleware('can:view,project')->group(function () {
            Route::apiResource('{project}/tasks', TaskController::class)
                ->except(['show', 'store', 'update', 'destroy']);
            Route::post('{project}/tasks', [TaskController::class, 'store'])
                ->middleware('can:addTask,project');
            Route::put('{project}/tasks/{task}', [TaskController::class, 'update'])
                ->middleware('can:updateTask,project');
            Route::delete('{project}/tasks/{task}', [TaskController::class, 'destroy'])
                ->middleware('can:deleteTask,project');
            Route::apiResource('{project}/teams', TeamController::class);
        });
    });

    Route::apiResource('projects', ProjectController::class)->except(['show', 'update', 'destroy']);
    Route::get('projects/{project}', [ProjectController::class, 'show'])->middleware('can:view,project');
    Route::put('projects/{project}', [ProjectController::class, 'update'])->middleware('can:update,project');
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->middleware('can:delete,project');
    
    Route::apiResource('taskteams', TeamTaskController::class);
    Route::get('tasks-user', [TaskController::class, 'getUserTeamTasks']);
});
