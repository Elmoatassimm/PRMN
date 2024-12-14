<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\BaseController;
use App\Models\{Team, Task, TeamTask};
use App\Services\ResponseService;
use Illuminate\Http\Request;

class TeamTaskController extends BaseController
{
    

    public function store(Request $request, Team $team, Task $task)
    {
        $request->validate([
            'task_ids' => ['required', 'array'],
            'task_ids.*' => ['exists:tasks,id']
        ]);

        $existingTasks = $team->tasks()->whereIn('tasks.id', $request->task_ids)->pluck('tasks.id');
        $newTaskIds = array_diff($request->task_ids, $existingTasks->toArray());

        foreach ($newTaskIds as $taskId) {
            TeamTask::create([
                'team_id' => $team->id,
                'task_id' => $taskId
            ]);
        }

        return $this->success(
            trans('messages.created'),
            $team->load('tasks')
        );
    }

    public function destroy(Team $team, Task $task)
    {
        $teamTask = TeamTask::where('team_id', $team->id)
            ->where('task_id', $task->id)
            ->first();

        if (!$teamTask) {
            return $this->error(trans('messages.not_found'), [], 404);
        }

        $teamTask->delete();

        return $this->success(trans('messages.deleted'));
    }
}
