<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\BaseController;
use App\Models\{Project, User, Team,Task};
use App\Services\ResponseService;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Log;

class TeamMemberController extends BaseController
{
    



    /**
     * Get the authenticated user's team assignments
     */
    public function myTeams(): JsonResponse
    {
        $user = auth()->user();
        $teams = $user->teams()
            ->with(['projects', 'tasks'])
            ->get();

        return $this->success(
            trans('messages.retrieved'),
            $teams
        );
    }

    

    /**
     * Update team member's task status
     */
    public function updateTaskStatus(Request $request, Task $task): JsonResponse
    {
        $task->update(['status' => $request->validate([
            'status' => ['string', 'in:pending,in_progress,completed'],
        ])['status']]);

        return $this->success(
            trans('messages.updated'),
            $task
        );
    }
}
