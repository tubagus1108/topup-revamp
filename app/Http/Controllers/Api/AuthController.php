<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('identity', 'password');

        $token = $this->authenticate($credentials, 'username');

        if (!$token) {
            $token = $this->authenticate($credentials, 'phone');
        }

        if (!$token) {
            return $this->respondFailedLogin();
        }

        return $this->respondWithToken($token);
    }

    public function register(RegisterRequest $request){
        try {
            $user = User::createNewUser([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'whatsapp' => $request['whatsapp'],
                'username' => $request['username'],
                'pin'=> Hash::make($request['pin']),
                'balance' => 0,
                'role' => "Member",
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully register!',
                'data' => $user,
            ], HttpResponse::HTTP_CREATED);

        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function authenticate($credentials, $field)
    {
        try {
            return JWTAuth::attempt([$field => $credentials['identity'], 'password' => $credentials['password']]);
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }
    }

    private function respondFailedLogin()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'These credentials do not match our records.',
        ], HttpResponse::HTTP_BAD_REQUEST);
    }

    private function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ], HttpResponse::HTTP_ACCEPTED);
    }
}
