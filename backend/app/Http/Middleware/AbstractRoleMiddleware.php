<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Models\User;
use App\Services\ProjectRoleService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Closure;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractRoleMiddleware
{
    protected $roleService;
    protected $responseService;

    public function __construct(
        ProjectRoleService $roleService, 
        ResponseService $responseService
    ) {
        $this->roleService = $roleService;
        $this->responseService = $responseService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract project ID from various possible sources
        $projectId = $this->extractProjectId($request);
        
        // Find the project model
        $project = Project::findOrFail($projectId);

        

        

        // If project is found, add it to route parameters
        if ($project) {
            $route = $request->route();
            $route->setParameter('project', $project);
            $route->parameters['project'] = $project;
        }

        $user = auth()->user();

        // Log access attempt for debugging and security monitoring
        Log::info('Role Access Attempt', [
            'user_id' => $user?->id,
            'project_id' => $projectId,
            'route' => $request->route()->getName() ?? $request->path(),
            'method' => $request->method(),
            'request_data' => $request->all(),
            'routes' => $request->route()
        ]);

        // Log route parameters for debugging
        Log::info('Route Parameters', [
            'project_id' => $projectId,
            'route' => $request->route()->getName() ?? $request->path(),
            'method' => $request->method(),
            'request_data' => $request->all(),
            'routes' => $request->route()->parameters
        ]);

        // Validate user authentication
        if (!$user) {
            return $this->unauthorized('auth.unauthenticated');
        }

        // Check user role
        if (!$this->checkRole($user, $project)) {
            return $this->unauthorized('roles.insufficient_permissions', [
                'required_role' => $this->getRequiredRole()
            ]);
        }

        return $next($request);
    }

    /**
     * Extract project ID from request
     */
    protected function extractProjectId(Request $request): ?int
    {
        return 
            $request->route('project') ?? 
            $request->route('projects') ?? 
            $request->input('project_id') ?? 
            $request->header('X-Project-ID')??
            $request->header('X-Project-Id')??null;
    }

    /**
     * Generate unauthorized response
     */
    protected function unauthorized(string $translationKey, array $params = []): Response
    {
        return $this->responseService->error(
            __($translationKey), 
            [], 
            403
        );
    }

    /**
     * Check if the user has the required role.
     */
    abstract protected function checkRole(User $user, ?Project $project): bool;

    /**
     * Get the required role name for error messages.
     */
    abstract protected function getRequiredRole(): string;
}
