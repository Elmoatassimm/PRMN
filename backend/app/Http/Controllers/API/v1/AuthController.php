<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoogleAuthRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\StoreUserService;
use App\Services\ResponseService;
use App\Services\InvitationService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $responseService;
    protected $invitationService;
    protected $storeUserService;

    public function __construct(ResponseService $responseService, InvitationService $invitationService, StoreUserService $storeUserService)
    {
        $this->responseService = $responseService;
        $this->invitationService = $invitationService;
        $this->storeUserService = $storeUserService;
        $locale = request()->get('lang', 'en');
        App::setLocale($locale);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $token = $request->input('invite_token');
        $roleData = $this->invitationService->determineRoleFromToken($token);

        if (!$roleData['success']) {
            return $this->responseService->error("invalid_or_expired_token", ["invalid_or_expired_token"], 400);
        }

        if ($roleData['role'] === 'admin') {
            $userData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'role' => 'admin',
            ];
        } else {
            // Create a new user using StoreUserService based on their role
            $userData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'role' => $roleData['role'],
                'invitable_id' => $roleData['invitation']['invitable_id'],
                'invitable_type' => $roleData['invitation']['invitable_type'],
                'invitation_id' => $roleData['invitation']['id'],
            ];
        }


        // Call the corresponding store method based on the user role
        $user = match ($roleData['role']) {
            'admin' => $this->storeUserService->storeAdmin($userData),
            'project_manager' => $this->storeUserService->storeProjectManager($userData),
            'team_member' => $this->storeUserService->storeTeamMember($userData),
            default => null,
        };

        if (!$user) {
            return $this->responseService->error(trans('messages.user_registration_failed'), 400);
        }

        // Generate a JWT token for the newly registered user
        $token = auth()->login($user);

        // Update the invitation status to 'Accepted' if a token was used
        if ($roleData['invitation']) {
            $roleData['invitation']->update(['status' => 'Accepted']);
        }

        return $this->responseService->success(
            trans('messages.user_registered_successfully'),
            [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
            201
        );
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return $this->responseService->error(trans('messages.invalid_credentials'), [], 401);
        }

        return $this->respondWithToken($token, trans('messages.login_successful'));
    }

    /**
     * Handle Google OAuth callback.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function GoogleAuth(GoogleAuthRequest $request)
    {
        $validatedData = $request->validated();

        // Attempt to find the user by email
        $authUser = User::firstOrNew(['email' => $validatedData['email']]);

        // Store the Google ID for existing users
        if (!$authUser->exists) {
            $authUser->google_id = $validatedData['id']; // Save Google ID for new users
        }

        // Handle login or registration
        return $authUser->exists ? $this.GoogleLogin($authUser) : $this.GoogleRegister($validatedData);
    }

    private function GoogleLogin(User $authUser)
    {
        // Check if the user has a role
        if (!$authUser->role) {
            return $this->responseService->error(trans('messages.please_select_role'), [], 400);
        }

        // Generate the token
        $token = auth()->login($authUser);

        if (!$token) {
            return $this->responseService->error('Token generation failed', [], 500);
        }

        return $this->responseService->success(
            trans('messages.login_successful'),

            [
                'user_id' => $authUser->id,
                'name' => $authUser->name,
                'email' => $authUser->email,
                'role' => $authUser->role,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        );
    }

    private function GoogleRegister(array $validatedData)
    {
        $token = request()->input('invite_token');
        $roleData = $this->invitationService->determineRoleFromToken($token);

        if (!$roleData['success']) {
            return $this->responseService->error("invalid_or_expired_token", ["invalid_or_expired_token"], 400);
        }

        $userData = [
            'name' => $validatedData['name'],
            'google_id' => $validatedData['id'],
            'avatar' => $validatedData['avatar'],
            'email' => $validatedData['email'],
            'password' => bcrypt(Str::random(16)), // Random password for Google Auth
            'email_verified_at' => now(),
            'role' => $roleData['role'],
            'invitable_id' => $roleData['invitation']['invitable_id'] ?? null,
            'invitable_type' => $roleData['invitation']['invitable_type'] ?? null,
            'invitation_id' => $roleData['invitation']['id'] ?? null,
        ];

        // Create user based on role
        $authUser = match ($roleData['role']) {
            'admin' => $this->storeUserService->storeAdmin($userData),
            'project_manager' => $this->storeUserService->storeProjectManager($userData),
            'team_member' => $this->storeUserService->storeTeamMember($userData),
            default => null,
        };

        if (!$authUser) {
            return $this->responseService->error(trans('messages.user_registration_failed'), 400);
        }

        // Update invitation status if applicable
        if ($roleData['invitation']) {
            $roleData['invitation']->update(['status' => 'Accepted']);
        }

        // Log in the new user and generate token
        $token = auth()->login($authUser);

        if (!$token) {
            return $this->responseService->error('Token generation failed', [], 500);
        }

        return $this->responseService->success(
            trans('messages.registration_successful'),
            [
                'user_id' => $authUser->id,
                'name' => $authUser->name,
                'email' => $authUser->email,
                'role' => $authUser->role,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        );
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->responseService->success(trans('messages.user_retrieved_successfully'), [
            'user' => auth()->user()
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return $this->responseService->success(trans('messages.logged_out_successfully'));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // Generate the token using the respondWithToken method
        $tokenData = $this->respondWithToken(auth()->refresh());

        // Use ResponseService to return the success response
        return $this->responseService->success(trans('messages.token_refreshed_successfully'), $tokenData);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     * @param  string|null $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $message = null)
    {
        $response = [
            'user' => auth()->user(),
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];

        if ($message) {
            $response['message'] = $message;
        }

        return $response; // Return the array instead of a JSON response
    }
}
