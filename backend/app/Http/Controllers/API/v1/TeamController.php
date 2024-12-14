<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreTeamRequest;
use App\Models\{Team, Project, ProjectTeam};
use App\Services\ResponseService;
use Illuminate\Http\Request;

class TeamController extends BaseController
{
    public function index(Project $project)
    {
        return $this->success(
            trans('messages.retrieved'), 
            $project->teams()->with(['tasks', 'users'])->get()
        );
    }

    public function store(StoreTeamRequest $request, Project $project)
    {
        $team = $project->teams()->create([
            'name' => $request->name,
            'created_by' => auth()->id(),
        ]);

        ProjectTeam::create([
            'project_id' => $project->id,
            'team_id' => $team->id,
        ]);

        return $this->success(trans('messages.created'), $team, 201);
    }

    public function update(Request $request, Team $team, Project $project)
    {

        
        if (!$project->teams->contains($team->id)) 
            return $this->error(trans('messages.not_found'), [], 404);
        

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $team->update($validated);
        
        return $this->success(trans('messages.updated'), $team);
    }

    public function destroy( Team $team ,Project $project)
    {
        if (!$project->teams->contains($team->id)) {
            return $this->error(trans('messages.not_found'), [], 404);
        }

        $team->delete();
        
        return $this->success(trans('messages.deleted'));
    }
}
