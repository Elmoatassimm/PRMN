<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Services\ResponseService;
use Illuminate\Support\Facades\App;

class ProjectController extends Controller
{
    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
        $locale = request()->get('lang', 'en');
        App::setLocale($locale);
    }

    public function index()
    {
        $user = auth()->user();
    
        $projects = $user->projects()
            ->with(['teams', 'users', 'tasks'])
            ->orWhere('created_by', $user->id)
            ->get()
            ->filter(fn($project) => $user->can('view', $project))
            ->values();
    
        return $this->responseService->success(trans('messages.retrieved'), ['data' => $projects]);
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

        return $this->responseService->success(trans('messages.created'), ['project' => $project], 201);
    }

    public function show(string $id)
    {
        $project = Project::with(['teams', 'users', 'tasks', 'comments'])->findOrFail($id);

        if (!auth()->user()->can('view', $project)) 
            return $this->responseService->error(trans('messages.unauthorized'), [], 403);

        return $this->responseService->success(trans('messages.retrieved'), ['project' => $project]);
    }

    public function update(UpdateProjectRequest $request, string $id)
    {
        $project = Project::findOrFail($id);

        if (!auth()->user()->can('update', $project)) 
            return $this->responseService->error(trans('messages.unauthorized'), [], 403);
        

        $project->update($request->validated());

        return $this->responseService->success(trans('messages.updated'), ['project' => $project]);
    }

    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);

        

        // Check permission to delete the project
        if (!auth()->user()->can('delete', $project)) 
            return $this->responseService->error(trans('messages.unauthorized'), [], 403);
        

        $project->delete();

        return $this->responseService->success(trans('messages.deleted'));
    }
}
