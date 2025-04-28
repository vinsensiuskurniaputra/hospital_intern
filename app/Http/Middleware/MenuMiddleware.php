<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Menu;

class MenuMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $userRole = Auth::user()->roles()->first();
            $menus = Menu::where('role_id', $userRole->id)
                        ->orderBy('order')
                        ->get();
            
            View::share('menus', $menus);
        }
        
        return $next($request);
    }
}
