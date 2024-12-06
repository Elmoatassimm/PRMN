<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreInvitedUserRequest;
use App\Models\InvitedUser;
use App\Services\{InvitationService, ResponseService};
use Illuminate\Support\Str;

class InvitedUserController extends BaseController
{
    protected $invitationService;

    public function __construct(InvitationService $invitationService, ResponseService $responseService)
    {
        
        $this->invitationService = $invitationService;
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invitations = InvitedUser::with(['invitable', 'inviter'])
            ->where('invited_by', auth()->id())
            ->latest()
            ->paginate(10);

        return $this->success(trans('messages.retrieved'), $invitations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvitedUserRequest $request)
    {
        $invitation = InvitedUser::create([
            ...$request->validated(),
            'token' => Str::random(32),
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(8),
        ]);

        // Send an invitation email (optional)
        // Mail::to($request->user_email)->send(new InvitationMail($invitation)); 

        return $this->success(trans('messages.created'), $invitation, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvitedUser $invitedUser)
    {
        if ($invitedUser->invited_by !== auth()->id()) {
            return $this->error(trans('messages.unauthorized'), [], 403);
        }

        $invitedUser->delete();

        return $this->success(trans('messages.deleted'));
    }
}
