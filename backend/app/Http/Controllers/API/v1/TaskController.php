<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use Illuminate\Support\Facades\App;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;


use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
        $locale = request()->get('lang', 'en');
        App::setLocale($locale);
    }


    public function getUserTeamTasks()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Get all teams that the user belongs to
        $teams = $user->teams;

        // Get all tasks associated with the user's teams
        $tasks = Task::whereHas('teams', function($query) use ($teams) {
            $query->whereIn('teams.id', $teams->pluck('id'));
        })
        ->with(['teams', 'subtasks'])
        ->get();

        return $this->responseService->success(trans('messages.retrieved'), [
            'tasks' => $tasks
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $project = Project::find($request->project_id);
        
        if (!$project) {
            return $this->responseService->notFound(trans('messages.not_found'));
        }

        if (!auth()->user()->can('view', $project)) {
            return $this->responseService->error(trans('messages.unauthorized'), [], 403);
        }

        $tasks = $project->tasks()
            ->with(['teams', 'subtasks'])
            ->get();

        return $this->responseService->success(trans('messages.retrieved'), ['tasks' => $tasks]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $project = Project::find($request->project_id);
        
        if (!$project) {
            return $this->responseService->notFound(trans('messages.not_found'));
        }

        if (!auth()->user()->can('addTask', $project)) {
            return $this->responseService->error(trans('messages.unauthorized'), [], 403);
        }

        $task = Task::create([
            'project_id' => $request->project_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
        ]);

        if ($request->has('team_ids')) {
            $task->teams()->attach($request->team_ids);
        }

        return $this->responseService->success(trans('messages.created'), ['task' => $task], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::with(['teams', 'subtasks', 'project'])->find($id);
        
        if (!$task) {
            return $this->responseService->notFound(trans('messages.not_found'));
        }

        if (!auth()->user()->can('view', $task->project)) {
            return $this->responseService->error(trans('messages.unauthorized'), [], 403);
        }

        return $this->responseService->success(trans('messages.retrieved'), ['task' => $task]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        $task = Task::find($id);
        
        if (!$task) {
            return $this->responseService->notFound(trans('messages.not_found'));
        }
/*
        if (!auth()->user()->can('update', $task->project)) {
            return $this->responseService->error(trans('messages.unauthorized'), [], 403);
        }*/

        $validatedData = $request->validated();
        
        $task->update($request->validated());

        if ($request->has('team_ids')) {
            $task->teams()->sync($request->team_ids);
        }

        return $this->responseService->success(trans('messages.updated'), ['task' => $task]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        
        if (!$task) {
            return $this->responseService->notFound(trans('messages.not_found'));
        }

        if (!auth()->user()->can('update', $task->project)) {
            return $this->responseService->error(trans('messages.unauthorized'), [], 403);
        }

        $task->delete();

        return $this->responseService->success(trans('messages.deleted'));
    }
}
