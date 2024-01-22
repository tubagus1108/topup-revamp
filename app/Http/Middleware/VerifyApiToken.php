<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $staticToken = $request->input('api_key');
        $user = User::where('token', $staticToken)->first();
        
        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // Menambahkan pengguna ke dalam request
        $request->attributes->add(['user' => $user]);
        
        return $next($request);
    }

}
