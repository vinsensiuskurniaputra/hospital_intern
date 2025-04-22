<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class MenuMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $menus = [];

        if ($user) {
            // Get menus for user's roles, sorted by order
            $menus = $user->roles()->with('menus')->get()
                ->pluck('menus')
                ->flatten()
                ->unique('id')
                ->sortBy('order');
        }

        View::share('menus', $menus);

        return $next($request);
    }
}
