<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderPrepaidRequest;
use App\Http\Requests\OrderStatusRequest;
use App\Models\LogTrx;
use App\Models\OrderPrepaid;
use App\Models\Services;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestApiController extends Controller
{
    //Get User Profile
    public function profile(Request $request)
    {
        $user = $request->get('user');
        return response()->json(['status' => 'success', 'message' => 'Success get profile', 'data' => $user]);
    }

    public function product(Request $request)
    {
        $user = $request->get('user')->role;
        try {
            $data = Services::getService($user);
            return response()->json(['status' => 'success', 'message' => 'Success get product-list', 'data' => $data]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }
    }

    public function order_prepaid(OrderPrepaidRequest $request)
    {
        $user = $request->get('user');

        $product = Services::getProductID($request->product, $user);

        // Check if the user has enough balance
        if ($user->balance < $product['price']) {
            return response()->json(['status' => 'error', 'message' => 'Insufficient balance']);
        }

        // Start the transaction
        DB::beginTransaction();

        try {
            $order = OrderPrepaid::createOrder([
                'user_id' => $user->id,
                'invoice_id' => $request['invoice_id'],
                'order_id' => $request['order_id'],
                'customer_no' => $request->target,
                'id_service' => $product['id'],
                'price' => $product['price'],
                'profit' => $product['profit'],
                'sid' => $product['sid'],
                'status' => 'Pending',
                'desc' => null,
                'transaction_type' => 'prepaid',
                'order_via' => 'api',
            ]);

            $response = OrderPrepaid::orederDigiflazz([
                'sid' => $product['sid'],
                'customer_no' => $order->customer_no,
                'order_id' => $order->order_id
            ]);

            LogTrx::createLog([
                'log' => json_encode($response),
            ]);

            // If the response from Digiflazz indicates insufficient balance, rollback the transaction and send an error response
            if ($response['data']['rc'] == '44') {
                DB::rollback();
                return response()->json(['status' => 'error', 'message' => 'Insufficient balance in Digiflazz']);
            }

            // Deduct the user's balance after a successful response from Digiflazz
            $user->balance -= $product['price'];
            $user->save();

            if ($response['data']['rc'] == '45') {
                $user->balance += $product['price'];
                $user->save();
                DB::rollback();
            }

            // If everything went smoothly, commit the transaction
            DB::commit();
        } catch (Exception $e) {
            // If there are any other errors, rollback the transaction and send an error response
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage()]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Success order prepaid',
            'data' => [
                'invoice' => $order['invoice'],
                'customer_no' => $order['customer_no'],
                'price' => $order['price'],
                'status' => $order['status'],
                'desc' => $order['desc'],
                'order_via' => $order['order_via'],
            ]
        ]);
    }

    public function status(OrderStatusRequest $request)
    {
        $user = $request->get('user');
        try {
            $data = OrderPrepaid::getStatus([
                'id_user' => $user->id,
                'invoice' => $request->invoice,
            ]);
            return response()->json(['status' => 'success', 'message' => 'Success get status order', 'data' => $data]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return response()->json(['status' => 'error', 'message' => 'Invoice not found'], 404);
        }
    }
}
