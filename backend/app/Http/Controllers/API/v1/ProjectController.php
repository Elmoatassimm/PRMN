<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\{StoreProjectRequest, UpdateProjectRequest};
use App\Models\{Project, ProjectUser};
use Illuminate\Support\Facades\Auth;

class ProjectController extends BaseController
{
    public function index()
    {
        $user = auth()->user();
    
        $projects = $user->projects()
            ->with(['teams', 'users', 'tasks'])
            ->orWhere('created_by', $user->id)
            ->get()
            ->filter(fn($project) => $user->can('view', $project))
            ->values();
        
        return $this->success(trans('messages.retrieved'), $projects);
    }

    public function store(StoreProjectRequest $request)
    {
        $user = auth()->user();

        $project = Project::create(array_merge($request->validated(), ['created_by' => $user->id]));
        
        ProjectUser::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'role_in_project' => $user->role,
        ]);

        return $this->success(trans('messages.created'), $project, 201);
    }

    public function show(Project $project)
    {
        return $this->success(trans('messages.retrieved'), $project->load(['teams', 'users', 'tasks', 'comments']));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return $this->success(trans('messages.updated'), $project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return $this->success(trans('messages.deleted'));
    }
}
