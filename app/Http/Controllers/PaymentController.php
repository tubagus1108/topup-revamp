<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Deposits;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class PaymentController extends Controller
{
    public function created(Request $request)
    {
        try {
            $payment = Payment::createdPayment([
                'name' => $request['name'],
                'image' => $request['image'],
                'code' => $request['code'],
                'type' => $request['type']
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully created payment method!',
                'data' => $payment,
            ], HttpResponse::HTTP_CREATED);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createdDeposit(Request $request)
    {
        $user = Auth::user()->id;
        $depositPending = Deposits::depositPending($user);
        if ($depositPending) {
            return response()->json(['status' => 'error', 'message' => 'Deposit anda masik ada yang pending'], HttpResponse::HTTP_BAD_GATEWAY);
        }

        try {
            $payment = Deposits::createdDeposit([
                'user_id' => $user,
                'method_id' => $request['method_id'],
                'payment_no' => $request['payment_no'],
                'amount' => $request['amount'],
                'status' => "Pending"
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully created deposit!',
                'data' => $payment,
            ], HttpResponse::HTTP_CREATED);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function status()
    {
        $user = Auth::user()->id;
        $status_deposit = Deposits::with('method_deposit')->where('user_id', $user)->get();

        return response()->json(['message' => 'Get deposit list success', 'data' => $status_deposit], 200);
    }

    public function list()
    {
        $list = Payment::all();
        return response()->json(['message' => 'Get payment method list success', 'data' => $list], 200);
    }
}
