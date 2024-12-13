<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use App\Services\ProjectRoleService;

class ProjectPolicy
{
    protected $roleService;

    public function __construct(ProjectRoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function viewAny(User $user)
    {
        return true; // Allow all authenticated users to view projects
    }

    public function view(User $user, Project $project)
    {
        return $this->roleService->isMember($user, $project);
    }

    public function create(User $user)
    {
        return $this->roleService->isAdmin($user); // Check if user has an admin role
    }

    public function update(User $user, Project $project)
    {
        return $this->roleService->isAdminOrProjectManager($user, $project);
    }

    public function delete(User $user, Project $project)
    {
        return $this->roleService->isAdmin($user, $project);
    }

    public function manageTeams(User $user, Project $project)
    {
        return $this->roleService->isAdmin($user, $project);
    }
    public function updateTask(User $user, Project $project)
    {
        return $this->roleService->isMember($user, $project);
    }
    public function addTask(User $user, Project $project)
    {
        return $this->roleService->isAdminOrProjectManager($user, $project);
    }
 
    public function deleteTask(User $user, Project $project)
    {
        return $this->roleService->isAdminOrProjectManager($user, $project);
    }
    public function comment(User $user, Project $project)
    {
        return $this->roleService->isMember($user, $project);
    }

    /**
     * Determine whether the user can add tasks to teams in the project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return bool
     */
    public function addTeamTasks(User $user, Team $team)
    {
        
        return $this->roleService->isAdminOrProjectManager($user, $team->projects);
    }

    /**
     * Determine whether the user can delete tasks from teams in the project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return bool
     */
    public function deleteTeamTasks(User $user, Project $project)
    {
        return $this->roleService->isAdminOrProjectManager($user, $project);
    }
}
