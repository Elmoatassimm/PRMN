<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
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

    public function manageUsers(User $user, Project $project)
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
}
