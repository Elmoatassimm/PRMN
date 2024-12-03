<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvitedUserRequest;
use Illuminate\Http\Request;
use App\Models\InvitedUser;


use Illuminate\Support\Str;

class InvitedUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvitedUserRequest $request)
    {
        // Get the authenticated user's ID
        $userId = auth()->id();

        // Generate a unique token for the invitation
        $token = Str::random(32);

        // Create the invited user record in the database
        $invitation = InvitedUser::create([
            'token' => $token,
            'expires_at' => now()->addDays(8),
            'user_email' => $request->user_email,
            'invitable_id' => $request->invitable_id,
            'invitable_type' => $request->invitable_type,
            'invited_by' => $userId,
            'status' => 'Pending',
        ]);

        // Send an invitation email (optional)
        // Mail::to($request->user_email)->send(new InvitationMail($invitation)); 

        return response()->json(['message' => 'Invitation created successfully', 'invitation' => $invitation], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
