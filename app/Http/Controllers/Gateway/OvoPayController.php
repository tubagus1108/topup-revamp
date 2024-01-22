<?php

namespace App\Http\Controllers\Gateway;

use App\Helpers\Ovo;
use App\Http\Controllers\Controller;
use App\Models\OvoPay;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OvoPayController extends Controller
{
    public function store(Request $request)
    {
        OvoPay::insert(
            [
                'phone' => $request->phone,
                'token' => $request->auth_token,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon::now('Asia/Jakarta')
            ]
        );

        return back()->with('status', 'Berhasil memasukkan ke database!');
    }

    public function GetOTP()
    {
        $nomor = config('services.ovopay.nomor');
        $app = new Ovo();
        $sendOTP = json_decode($app->sendOtp('+' . $nomor), true);
        $refId = $sendOTP['data']['otp']['otp_ref_id'];

        return response()->json([
            'status' => "True",
            'refID' => $refId
        ]);
    }

    public function VerifOTP(Request $request)
    {
        $nomor = config('services.ovopay.nomor');
        $init = new Ovo();
        $verifOTP = json_decode($init->OTPVerify('+' . $nomor, $request->refID, $request->otp), true);
        $accToken = $verifOTP['data']['otp']['otp_token'];

        return response()->json([
            'status' => "True",
            'updateToken' => $accToken
        ]);
    }

    public function VerifPIN(Request $request)
    {
        $nomor = config('services.ovopay.nomor');
        $init = new Ovo();
        $verifPIN = json_decode($init->getAuthToken('+' . $nomor, $request->refID, $request->update_token, $request->pin), true);
        $authToken = $verifPIN['data']['auth']['refresh_token'];

        OvoPay::insert([
            'phone' => $nomor,
            'token' => $authToken,
            'created_at' => Carbon::now('Asia/Jakarta'),
            'updated_at' => Carbon::now('Asia/Jakarta')
        ]);

        return response()->json([
            'status' => "True",
            'auth_token' => $authToken,
        ]);
    }
}
