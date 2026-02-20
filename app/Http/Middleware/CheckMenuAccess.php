<?php

namespace App\Http\Middleware;

use App\Services\MenuService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuAccess
{
    public function __construct(
        protected MenuService $menuService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('auth.login');
        }

        if (! $user->is_active) {
            Auth::logout();

            return redirect()->route('auth.login')
                ->with('error', 'Your account has been deactivated.');
        }

        $routeName = $request->route()->getName();

        // Check if the route is even managed by the Menu system
        // If a route is NOT in the menus table, it's considered a "general" route
        // that any authenticated user can access (e.g. profile, switch-role, dashboard if not in menu, etc.)
        $isManagedRoute = \App\Models\Menu::where('route', $routeName)->exists();

        if (! $isManagedRoute) {
            return $next($request);
        }

        // For managed routes, check if the user's role has access
        if (! $this->menuService->userHasAccessToRoute($routeName)) {
            abort(403, 'You do not have access to this page.');
        }

        return $next($request);
    }
}
