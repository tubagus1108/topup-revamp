<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        // Mendapatkan pengguna dari request
        $user = $request->get('user');

        $allowed_roles = ['Member', 'Gold', 'Platinum'];

        if (!in_array($user->role, $allowed_roles)) {
            return response()->json(['message' => 'Unauthorized: you do not have the required role'], 401);
        }

        return $next($request);
    }

}
