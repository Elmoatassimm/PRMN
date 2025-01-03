<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\{StoreTaskRequest, UpdateTaskRequest};
use App\Models\{Task, Project, TeamTask};
use Illuminate\Support\Facades\Auth;

class TaskController extends BaseController
{
    public function index(Project $project)
    {
        return $this->success(
            trans('messages.retrieved'),
            $project->tasks()->with(['teams', 'subtasks'])->get()
        );
    }

    public function store(StoreTaskRequest $request, Project $project)
    {
        $validatedData = $request->validated();
        $validatedData['project_id'] = $project->id;
        $task = Task::create($validatedData);

        if ($request->has('team_id')) {
            TeamTask::create([
                'team_id' => $request->team_id,
                'task_id' => $task->id
            ]);
        }

        return $this->success(trans('messages.created'), $task, 201);
    }

    public function update(UpdateTaskRequest $request, Task $task ,Project $project)
    {
        $task->update($request->validated());
        return $this->success(trans('messages.updated'), $task);
    }

    public function destroy( Task $task)
    {
        $task->delete();
        return $this->success(trans('messages.deleted'));
    }
}
