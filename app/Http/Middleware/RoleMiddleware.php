<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        $user = Auth::user();
    
        if ($user->role->role_name !== $role) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
    
        return $next($request);
    }
}
