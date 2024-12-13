<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Models\User;
use App\Services\ProjectRoleService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TeamMemberMiddleware extends AbstractRoleMiddleware
{
    protected $roleService;

    public function __construct(ProjectRoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    protected function checkRole(User $user, ?Project $project): bool
    {
        if (!$project) {
            Log::warning('Team Member Access: No project specified', [
                'user_id' => $user->id,
                'attempt' => 'Team Member Access'
            ]);
            return false;
        }

        $isMember = $this->roleService->isMember($user, $project);

        Log::info('Team Member Access Check', [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'is_member' => $isMember
        ]);

        return $isMember;
    }

    protected function getRequiredRole(): string
    {
        return trans('roles.team_member');
    }
}
