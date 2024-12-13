<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Models\User;
use App\Services\ProjectRoleService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware extends AbstractRoleMiddleware
{
    protected $roleService;

    public function __construct(ProjectRoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    protected function checkRole(User $user, ?Project $project): bool
    {
        if (!$project) {
            Log::warning('Admin Access: No project specified', [
                'user_id' => $user->id,
                'attempt' => 'Admin Access'
            ]);
            return false;
        }

        $isAdmin = $this->roleService->isAdmin($user, $project);

        Log::info('Admin Access Check', [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'is_admin' => $isAdmin
        ]);

        return $isAdmin;
    }

    protected function getRequiredRole(): string
    {
        return trans('roles.admin');
    }
}
