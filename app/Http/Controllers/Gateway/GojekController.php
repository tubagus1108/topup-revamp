<?php

namespace App\Http\Controllers\Gateway;

use App\Helpers\GojekPay;
use App\Http\Controllers\Controller;
use App\Models\Gopay;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GojekController extends Controller
{
    public function create()
    {
    }

    public function store(Request $request)
    {
        Gopay::insert(
            [
                'phone' => $request->phone,
                'token' => $request->auth_token,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon::now('Asia/Jakarta')
            ]
        );

        return back()->with('status', 'Berhasil memasukkan ke database!');
    }

    public function GetOTP($no)
    {
        $app = new GojekPay();
        $get_otp = json_decode($app->loginRequest($no), true);
        $otp_token = $get_otp['data']['otp_token'];
        return response()->json([
            'status' => 'True',
            'otp_token' => $otp_token,
        ]);
    }

    public function VerifOTP(Request $request)
    {
        $app = new GojekPay;
        $Auth = json_decode($app->getAuthToken($request->otp_token, $request->otp), true);
        $accessToken = $Auth['access_token'];

        return response()->json([
            'status' => 'True',
            'auth_token' => $accessToken
        ]);
    }
}
