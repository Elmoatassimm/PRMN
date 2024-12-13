<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreInvitedUserRequest;
use App\Models\{InvitedUser, Project, Team};
use App\Services\{InvitationService, ResponseService};
use Illuminate\Support\Str;

class InvitedUserController extends BaseController
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invitations = InvitedUser::with(['invitable', 'inviter'])
            ->where('invited_by', auth()->id())
            ->latest()->get();

            

            

        return $this->success(trans('messages.retrieved'), $invitations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvitedUserRequest $request, Project $project)
    {
    // Use destructuring to extract values from the request
    $userEmail = $request->get('user_email');
    $invitableType = $request->get('invitable_type');
    $invitableId = $request->get('invitable_id');

    // Get the invitable model (Project or Team) and avoid unnecessary database queries if invitable_type is invalid
    $invitable = $invitableType::findOrFail($invitableId);

    // Check if the user has the right to invite based on the project and team relationship
    if ($invitableType === 'App\Models\Team' && !$project->teams()->where('teams.id', $invitableId)->exists()) {
        return $this->error(trans('messages.unauthorized'), [], 403);
    }

    // Create the invitation in a single call, ensuring only necessary data is passed
    $invitationData = [
        'user_email' => $userEmail,
        'token' => Str::random(32),
        'invited_by' => auth()->id(),
        'expires_at' => now()->addDays(8),
    ];
    
    // Create the invitation and return success response
    $invitation = $invitable->invitedUsers()->create($invitationData);

    // Optionally send an invitation email
    // Mail::to($userEmail)->send(new InvitationMail($invitation));

    return $this->success(trans('messages.created'), $invitation, 201);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $invitedUser )
    {
        
        InvitedUser::destroy($invitedUser);

        return $this->success(trans('messages.deleted'));
    }
}
