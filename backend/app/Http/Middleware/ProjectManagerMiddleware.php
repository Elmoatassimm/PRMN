<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Models\User;
use App\Services\ProjectRoleService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProjectManagerMiddleware extends AbstractRoleMiddleware
{
    protected $roleService;

    public function __construct(ProjectRoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    protected function checkRole(User $user, ?Project $project): bool
    {
        if (!$project) {
            Log::warning('Project Manager Access: No project specified', [
                'user_id' => $user->id,
                'attempt' => 'Project Manager Access'
            ]);
            return false;
        }

        $isProjectManager = $this->roleService->isAdminOrProjectManager($user, $project);

        Log::info('Project Manager Access Check', [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'is_project_manager' => $isProjectManager
        ]);

        return $isProjectManager;
    }

    protected function getRequiredRole(): string
    {
        return trans('roles.project_manager');
    }
}
