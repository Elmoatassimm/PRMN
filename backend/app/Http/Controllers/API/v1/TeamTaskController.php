<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\TeamTask;
use App\Services\ResponseService;
use Illuminate\Http\Request;

class TeamTaskController extends Controller
{
    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teamTasks = TeamTask::with(['team', 'task'])->get();
        return $this->responseService->success('Team tasks retrieved successfully', ['teamTasks' => $teamTasks]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'task_id' => 'required|exists:tasks,id'
        ]);

        $teamTask = TeamTask::create($validated);

        return $this->responseService->success('Team task created successfully', ['teamTask' => $teamTask], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teamTask = TeamTask::with(['team', 'task'])->find($id);

        if (!$teamTask) {
            return $this->responseService->notFound('Team task not found');
        }

        return $this->responseService->success('Team task retrieved successfully', ['teamTask' => $teamTask]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $teamTask = TeamTask::find($id);

        if (!$teamTask) {
            return $this->responseService->notFound('Team task not found');
        }

        $validated = $request->validate([
            'team_id' => 'sometimes|required|exists:teams,id',
            'task_id' => 'sometimes|required|exists:tasks,id'
        ]);

        $teamTask->update($validated);

        return $this->responseService->success('Team task updated successfully', ['teamTask' => $teamTask]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teamTask = TeamTask::find($id);

        if (!$teamTask) {
            return $this->responseService->notFound('Team task not found');
        }

        $teamTask->delete();

        return $this->responseService->success('Team task deleted successfully');
    }
}
