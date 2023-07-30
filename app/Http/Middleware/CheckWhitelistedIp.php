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
        $user_ip = $request->ip();
        // Anda sudah menambahkan pengguna ke dalam request di middleware 'verifiedToken'
        $user = $request->get('user');

        // Ambil whitelist IP dari pengguna tersebut
        $whitelisted_ips = $user->whitelist_ip;

        // Periksa jika IP pengguna ada dalam daftar IP yang diperbolehkan
        if (!in_array($user_ip, explode(",", $whitelisted_ips))) {
            return response()->json(['message' => 'Unauthorized: your IP is not whitelisted'], 401);
        }

        return $next($request);
    }
}
