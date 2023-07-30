<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPrepaid extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'order_prepaid';
    protected $gaurded = [];

    protected $fillable = [
        'order_id',
        'customer_no',
        'id_service',
        'price',
        'profit',
        'sid',
        'status',
        'desc',
        'transaction_type'
    ];

    public static function createOrder(array $request){
        $order = new OrderPrepaid([
            'order_id' => $request['order_id'],
            'customer_no' => $request['customer_no'],
            'id_service' => $request['id_service'],
            'price' => $request['price'],
            'profit' => $request['profit'],
            'sid' => $request['sid'],
            'status' => $request['status'],
            'desc' => $request['desc'],
            'transaction_type' => $request['transaction_type'],
        ]);
        
        $order->save();

        return $order;
    }


    public static function connect($baseUrl,$data,$header){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl."transaction");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $chresult = curl_exec($ch);
        curl_close($ch);
        $json_result = json_decode($chresult, true);
        return $json_result;
    }
}
