<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWhitelistedIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get user IP
        $user_ip = $request->ip();

        // Get user from the request attributes set by the 'verifiedToken' middleware
        $user = $request->get('user');

        // Check if the user is null or whitelist_ip is not set
        if (!$user || !isset($user->whitelist_ip)) {
            return response()->json(['message' => 'Unauthorized: user data not found or IP not whitelisted'], 401);
        }

        // Get the whitelist IP addresses as an array
        $whitelisted_ips = explode(",", $user->whitelist_ip);

        // Trim the IP addresses to remove any white spaces around them
        $whitelisted_ips = array_map('trim', $whitelisted_ips);

        // Check if the user's IP is in the list of whitelisted IPs
        if (!in_array($user_ip, $whitelisted_ips)) {
            return response()->json(['message' => 'Unauthorized: your IP is not whitelisted'], 401);
        }

        // If the user's IP is whitelisted, proceed with the request
        return $next($request);
    }

}
