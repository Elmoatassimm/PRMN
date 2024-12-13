<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\{StoreSubTaskRequest, UpdateSubTaskRequest};
use App\Models\{Task, SubTask};
use App\Services\ResponseService;

class SubTaskController extends BaseController
{
    public function store(StoreSubTaskRequest $request, Task $task)
    {
        $subtask = $task->subtasks()->create([
            ...$request->validated(),
            'is_completed' => $request->is_completed ?? false,
        ]);

        return $this->success(trans('messages.created'), $subtask, 201);
    }

    /**
     * Display the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubTaskRequest $request, Task $task, SubTask $subtask)
    {
        if ($subtask->task_id !== $task->id) {
            return $this->error(trans('messages.not_found'), [], 404);
        }

        $subtask->update($request->validated());

        return $this->success(trans('messages.updated'), $subtask);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, SubTask $subtask)
    {
        if ($subtask->task_id !== $task->id) {
            return $this->error(trans('messages.not_found'), [], 404);
        }

        $subtask->delete();

        return $this->success(trans('messages.deleted'));
    }
}
