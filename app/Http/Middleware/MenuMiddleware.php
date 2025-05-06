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

        $userRole = Auth::user()->roles()->first()->name;

        $profileRoute = '#';
        if ($userRole == 'student') {
            $profileRoute = route('student.profile');
        } elseif ($userRole == 'pic') {
            $profileRoute = route('responsible.profile');
        } else {
            // Default untuk admin atau role lain
            $profileRoute = route('home');
        }

        $menus = [];

        if ($user) {
            // Get menus for user's roles, sorted by order
            $menus = $user->roles()->with('menus')->get()
                ->pluck('menus')
                ->flatten()
                ->unique('id')
                ->sortBy('order');
        }

        View::share('menusSideBar', $menus);
        View::share('profileRoute', $profileRoute);

        return $next($request);
    }
}
