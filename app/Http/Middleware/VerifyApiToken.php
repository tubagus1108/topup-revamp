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
    public function handle(Request $request, Closure $next): Response
    {
        $staticToken = $request->header('Authorization');

        // Lakukan pengecekan token di sini dengan mengambil data pengguna berdasarkan token dari tabel pengguna
        $user = User::where('token', $staticToken)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid static token'], 401);
        }

        // Simpan data pengguna dalam instance $request untuk digunakan pada controller
        $request->merge(['user' => $user]);

        return $next($request);
    }
}
