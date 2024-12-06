<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreTeamRequest;
use App\Models\ProjectTeam;
use App\Models\Team;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Models\Project;

class TeamController extends BaseController
{
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
        $locale = request()->get('lang', 'en');
        App::setLocale($locale);
    }

    public function index(Request $request, Project $project)
    {
        if (!auth()->user()->can('view', $project)) 
            return $this->error(trans('messages.unauthorized'), [], 403);

        return $this->success(trans('messages.retrieved'), $project->teams);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeamRequest $request, Project $project)
    {
        if (!auth()->user()->can('manageUsers', $project)) 
            return $this->error(trans('messages.unauthorized'), [], 403);

        $team = Team::create([
            'name' => $request->name,
            'created_by' => auth()->id(),
        ]);

        $project->teams()->attach($team->id);

        return $this->success(trans('messages.created'), $team, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $team = Team::with(['users', 'projects', 'tasks', 'creator'])->find($id);
        
        if (!$team) {
            return $this->error('Team not found', [], 404);
        }

        return $this->success('Team retrieved successfully', ['team' => $team]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $team = Team::find($id);

        if (!$team) {
            return $this->error('Team not found', [], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $team->update([
            'name' => $validated['name']
        ]);

        if ($request->has('user_ids')) {
            $team->users()->sync($request->user_ids);
        }

        return $this->success('Team updated successfully', ['team' => $team]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Team $team)
    {
        if (!auth()->user()->can('manageUsers', $project)) 
            return $this->error(trans('messages.unauthorized'), [], 403);

        $team->delete();

        return $this->success(trans('messages.deleted'));
    }
}
