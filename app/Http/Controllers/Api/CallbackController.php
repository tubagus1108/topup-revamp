<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PaymentHelper;
use App\Http\Controllers\Controller;
use App\Models\LogTrx;
use App\Models\OrderPrepaid;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    public function callback(Request $request)
    {
        $secret = 'Satuhati1108';
        $post_data = $request->getContent();
        $signature = hash_hmac('sha1', $post_data, $secret);
        if ($request->header('X-Hub-Signature') !== 'sha1=' . $signature) {
            return response()->json(['status' => "error", 'message' => 'Invalid signature'], 400);
        }

        $dataArray = json_decode($request->getContent(), true)['data'];
        Log::info("=== RESPONSE CALLBACK ===", $dataArray);
        $status = PaymentHelper::DFStatus($dataArray['message']);
        $trxid = $dataArray['trx_id']; // ID Transaksi DigiFlazz
        $refid = $dataArray['ref_id']; // ID Transaksi dari Panel
        $note = $dataArray['sn'];
        $price = $dataArray['price'];
        $messages = $dataArray['message'];
        $last = $dataArray['buyer_last_saldo'];

        $check_order = OrderPrepaid::where('order_id', $refid)->where('status', 'Pending')->first();

        if ($check_order === null) {
            return response()->json(['status' => "error", 'message' => 'Order not found'], 404);
        }

        if ($status == "Fail") {
            $refund = User::where('id', $check_order->user_id)->first();

            if ($refund !== null) {
                User::where('id', $check_order->user_id)->update([
                    'balance' => $refund->balance + $check_order->price,
                ]);

                Log::info("RESPONSE FAIL", ['response' => $status]);
            } else {
                // Handle the case when the user is not found
                Log::error("User not found for ID: " . $check_order->user_id);
            }
        }
        // Perform model-specific processing by calling a static method on OrderPulsa
        $response = OrderPrepaid::handleCallback($check_order, $status, $messages, $note, $price);

        return response()->json(['success' => $response]);
    }
}
