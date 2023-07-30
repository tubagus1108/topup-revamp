<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        // Anda sudah menambahkan pengguna ke dalam request di middleware 'verifiedToken'
        $user = $request->get('user');

        // Buat array berisi peran yang diizinkan
        $allowed_roles = ['Member', 'Gold', 'Platinum'];

        // Periksa jika peran pengguna berada di dalam array peran yang diizinkan
        if (!in_array($user->role, $allowed_roles)) {
            return response()->json(['message' => 'Unauthorized: you do not have the required role'], 401);
        }

        return $next($request);
    }

}
