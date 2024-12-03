<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Models\ProjectUser;

class ProjectRoleService
{
    /**
     * Check if the user is a member of the project.
     */
    public function isMember(User $user, Project $project): bool
    {
        return ProjectUser::where('project_id', $project->id)
                          ->where('user_id', $user->id)
                          ->exists();
    }

    /**
     * Check if the user is an admin of the project.
     */
    public function isAdmin(User $user, Project $project): bool
    {
        return ProjectUser::where('project_id', $project->id)
                          ->where('user_id', $user->id)
                          ->where('role_in_project', 'admin')
                          ->exists();
    }

    /**
     * Check if the user is an admin or project manager of the project.
     */
    public function isAdminOrProjectManager(User $user, Project $project): bool
    {
        return ProjectUser::where('project_id', $project->id)
                          ->where('user_id', $user->id)
                          ->whereIn('role_in_project', ['admin', 'project_manager'])
                          ->exists();
    }
}
