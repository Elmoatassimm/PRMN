<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\ProjectTeam;
use App\Models\Team;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TeamController extends Controller
{
    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate input
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'project_id' => 'required|exists:projects,id',
         // Ensure user IDs are valid
    ]);

    // Create team
    $team = Team::create([
        'name' => $validated['name'],
        'created_by' => auth()->user()->id, // Use auth() helper
    ]);

    // Associate the team with the project
    ProjectTeam::create([
        'project_id' => $validated['project_id'],
        'team_id' => $team->id,

    ]);

    return $this->responseService->success('Team created successfully', ['team' => $team], 201);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $team = Team::with(['users', 'projects', 'tasks', 'creator'])->find($id);
        
        if (!$team) {
            return $this->responseService->notFound('Team not found');
        }

        return $this->responseService->success('Team retrieved successfully', ['team' => $team]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $team = Team::find($id);

        if (!$team) {
            return $this->responseService->notFound('Team not found');
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

        return $this->responseService->success('Team updated successfully', ['team' => $team]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $team = Team::find($id);

        if (!$team) {
            return $this->responseService->notFound('Team not found');
        }

        $team->delete();

        return $this->responseService->success('Team deleted successfully');
    }
}
