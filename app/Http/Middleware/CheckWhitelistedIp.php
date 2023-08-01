<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckWhitelistedIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user_ip = $request->ip();

        // Mendapatkan pengguna dari request
        $user = $request->get('user');

        if (!$user || !isset($user->whitelist_ip)) {
            return response()->json(['message' => 'Unauthorized: user data not found or IP not whitelisted'], 401);
        }

        $whitelisted_ips = explode(",", $user->whitelist_ip);
        $whitelisted_ips = array_map('trim', $whitelisted_ips);

        if (!in_array($user_ip, $whitelisted_ips)) {
            return response()->json(['message' => 'Unauthorized: your IP is not whitelisted'], 401);
        }

        return $next($request);
    }


}
