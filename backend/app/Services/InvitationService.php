<?php

namespace App\Services;

use App\Models\InvitedUser;
use Carbon\Carbon;

class InvitationService
{
    /**
     * Validate the token and determine the user's role.
     *
     * @param string|null $token
     * @return array
     */
    public function determineRoleFromToken(?string $token): array
    {
        if ($token) {
            // Retrieve the invitation based on the token
            $invitation = InvitedUser::where('token', $token)
                ->where('expires_at', '>', Carbon::now())
                ->where('status', 'Pending')
                ->first();

            // Validate the token's existence and status
            if (!$invitation) {
                return [
                    'success' => false,
                    'role' => null,
                    'message' => trans('messages.invalid_or_expired_token'),
                ];
            }

            // Determine the role based on the invitable_type
            $role = $invitation->invitable_type === 'App\Models\Project' ? 'project_manager' : 'team_member';

            return [
                'success' => true,
                'role' => $role,
                'invitation' => $invitation,
            ];
        }

        // Default to 'Admin' role if no token is provided
        return [
            'success' => true,
            'role' => 'admin',
            'invitation' => null,
        ];
    }
}
