<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $userId = auth()->id();
        $projects = Project::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->orWhere('created_by', $userId)
            ->with(['teams', 'users','tasks'])
            ->get()
            ->filter(function ($project) {
                return auth()->user()->can('view', $project);
            })
            ->values();

        return $this->responseService->success(trans('messages.retrieved'), ['data' => $projects]);
    }

    public function store(StoreProjectRequest $request)
    {


        $userId = auth()->id();

        $project = Project::create(array_merge($request->validated(), ['created_by' => $userId]));

        ProjectUser::create([
            'user_id' => $userId,
            'project_id' => $project->id,
            'role_in_project' => 'admin',
        ]);

        return $this->responseService->success(trans('messages.created'), ['project' => $project], 201);
    }

    public function show(string $id)
    {
        $project = Project::with(['teams', 'users', 'tasks', 'comments'])->find($id);

        if (!$project) {
            return $this->responseService->error(trans('messages.not_found'), [], 404);
        }

        // Check permission to view the specific project
        if (!auth()->user()->can('view', $project)) {
            return $this->responseService->error(trans('messages.unauthorized'), ['Unauthorized'], 403);
        }

        return $this->responseService->success(trans('messages.retrieved'), ['project' => $project]);
    }

    public function update(UpdateProjectRequest $request, string $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return $this->responseService->error(trans('messages.not_found'), [], 404);
        }

        // Check permission to update the project
        if (!auth()->user()->can('update', $project)) {
            return $this->responseService->error(trans('messages.unauthorized'), [], 403);
        }

        $project->update($request->validated());

        return $this->responseService->success(trans('messages.updated'), ['project' => $project]);
    }

    public function destroy(string $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return $this->responseService->error(trans('messages.not_found'), [], 404);
        }

        // Check permission to delete the project
        if (!auth()->user()->can('delete', $project)) {
            return $this->responseService->error(trans('messages.unauthorized'), [], 403);
        }

        $project->delete();

        return $this->responseService->success(trans('messages.deleted'));
    }
}
