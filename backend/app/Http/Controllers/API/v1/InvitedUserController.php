<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvitedUserRequest;
use Illuminate\Http\Request;
use App\Models\InvitedUser;
use App\Services\InvitationService;
use App\Services\ResponseService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Http\JsonResponse;

class InvitedUserController extends Controller
{
    protected $invitationService;
    protected $responseService;

    public function __construct(InvitationService $invitationService, ResponseService $responseService)
    {
        $this->invitationService = $invitationService;
        $this->responseService = $responseService;
        $locale = request()->get('lang', 'en');
        App::setLocale($locale);
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

        return $this->responseService->success(
            trans('messages.retrieved'),
            ['invitations' => $invitations]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvitedUserRequest $request)
    {
        $request->merge([
            // Generate a unique token for the invitation
            'token' => Str::random(32),
            // Get the authenticated user's ID
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(8),
        ]);

        $invitation = InvitedUser::create($request->all());

        // Send an invitation email (optional)
        // Mail::to($request->user_email)->send(new InvitationMail($invitation)); 

        return $this->responseService->success(
            trans('messages.created'),
            ['invitation' => $invitation],
            201
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invitation = InvitedUser::findOrFail($id);

        // Check if user has permission to delete this invitation
        if ($invitation->invited_by !== auth()->id()) {
            return $this->responseService->error(
                trans('messages.unauthorized'),
                [],
                403
            );
        }

        $invitation->delete();

        return $this->responseService->success(
            trans('messages.deleted'),
            []
        );
    }

    
}
