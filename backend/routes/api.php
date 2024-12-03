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
    Route::apiResource('invitedusers', InvitedUserController::class);

    Route::post('/assign-role', [AuthController::class, 'assignRoleToAuthUser'])->name('assign-role');
});

// Google authentication route
Route::post('/v1/auth/google-auth', [AuthController::class, 'GoogleAuth']);

// Protected routes for projects, tasks and teams
Route::group([
    'middleware' => ['api', 'auth:api'], // Authentication required
    'prefix' => 'v1'
], function () {
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('teams', TeamController::class);
    Route::apiResource('taskteams', TeamTaskController::class);
    Route::get('tasks-user', [TaskController::class, 'getUserTeamTasks']);
});

