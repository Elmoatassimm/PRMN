
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\{
    AuthController,
    InvitedUserController,
    ProjectController,
    TaskController,
    TeamController,
    TeamTaskController,
    SubTaskController,
    CommentController
};

// Auth routes  

Route::group(['middleware' => ['api', 'auth:api'], 'prefix' => 'v1/auth'], function () {
    Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware('auth:api')->name('register');
    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('auth:api')->name('login');
    Route::post('/google-auth', [AuthController::class, 'GoogleAuth'])->withoutMiddleware('auth:api')->name('google.auth');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->name('me');
});

// Protected routes for projects, tasks and teams
Route::group(['middleware' => ['api', 'auth:api'], 'prefix' => 'v1'], function () {
    
    
        

       

        // Comment routes (team member access)
        Route::middleware('team-member')->group(function () {
            Route::get('comments', [CommentController::class, 'index']);
            Route::post('comments', [CommentController::class, 'store']);
            Route::delete('comments/{comment}', [CommentController::class, 'destroy']);

            // Team routes
        Route::apiResource('teams', TeamController::class)
        ->except(['store', 'update', 'destroy']);

        // Basic task routes for team members
        Route::apiResource('tasks', TaskController::class)->except(['show', 'store', 'update', 'destroy']);

        });

        
            
        
        


    // Team task routes
    Route::middleware('project-manager')->group(function () {
        Route::post('teams/{team}/tasks', [TeamTaskController::class, 'store']);
        Route::delete('teams/{team}/tasks/{task}', [TeamTaskController::class, 'destroy']);
        
        // Subtask routes
        Route::post('tasks/{task}/subtasks', [SubTaskController::class, 'store']);
        Route::put('tasks/{task}/subtasks/{subtask}', [SubTaskController::class, 'update']);
        Route::delete('tasks/{task}/subtasks/{subtask}', [SubTaskController::class, 'destroy']);
       
// team routes
        Route::post('teams', [TeamController::class, 'store']);
            Route::put('teams/{team}', [TeamController::class, 'update']);
            Route::delete('teams/{team}', [TeamController::class, 'destroy']);
// Task routes
            Route::post('tasks', [TaskController::class, 'store']);
            Route::put('tasks/{task}', [TaskController::class, 'update']);
            Route::delete('tasks/{task}', [TaskController::class, 'destroy']);
    });

    
    Route::apiResource('projects', ProjectController::class)->only(['index']);
      // project routes
      Route::put('projects/{project}', [ProjectController::class, 'update']);
      Route::delete('projects/{project}', [ProjectController::class, 'destroy']);
    

    Route::middleware('admin')->post('projects', [ProjectController::class, 'store']);
    // Invitation routes (Admin only)
    Route::middleware('admin')->group(function () {
        Route::post('invitations', [InvitedUserController::class, 'store']);
        Route::delete('invitations/{invitation}', [InvitedUserController::class, 'destroy']);
    });
});
