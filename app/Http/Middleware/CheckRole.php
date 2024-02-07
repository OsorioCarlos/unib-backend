<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        $user = Auth::user();
        $allowedRoles = explode(',', strtoupper($roles));

        if ($user && in_array(strtoupper($user->tipoCatalogo->nombre), $allowedRoles)) {
            return $next($request);
        }
        abort(403, 'Unauthorized action.');
    }
}
