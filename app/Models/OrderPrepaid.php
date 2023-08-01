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
        'user_id',
        'invoice',
        'order_id',
        'customer_no',
        'id_service',
        'price',
        'profit',
        'sid',
        'status',
        'desc',
        'transaction_type',
        'order_via',
    ];

    protected $hidden = [
        'user_id',
        'id',
        'order_id',
        'id_service',
        'profit',
        'sid',
        'transaction_type',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function invoiceOrder(){
        $unik = date('Hs');
        $kode_unik = substr(str_shuffle(1234567890),0,3);
        $order_id = 'INV'.$unik.$kode_unik.'RM';
        return $order_id;
    }

    public static function generetRefId(){
        return self::acak_nomor(3) . self::acak_nomor(4);
    }

    public static function createOrder(array $request){
        $order = new OrderPrepaid([
            'user_id'=> $request['user_id'],
            'invoice' => self::invoiceOrder(),
            'order_id' => self::generetRefId(),
            'customer_no' => $request['customer_no'],
            'id_service' => $request['id_service'],
            'price' => $request['price'],
            'profit' => $request['profit'],
            'sid' => $request['sid'],
            'status' => $request['status'],
            'desc' => $request['desc'],
            'transaction_type' => $request['transaction_type'],
            'order_via' => $request['order_via'],
        ]);
        
        $order->save();

        return $order;
    }

    public static function orederDigiflazz(array $request){
        // Mengambil kredensial API Digiflazz dari konfigurasi Laravel
        $username = config('services.digiflazz.username');
        $secretKey = config('services.digiflazz.secret_key');
        $baseUrl = config('services.digiflazz.base_url');
        
        // Membuat tanda tangan (signature) untuk permintaan API
        $signature = md5($username . $secretKey . $request['order_id']);
        
        // Menyiapkan data permintaan API
        $data = [
            'username' => $username,
            'buyer_sku_code' => $request['sid'],
            'customer_no' => $request['customer_no'],
            'ref_id' => $request['order_id'],
            'sign' => $signature,
            'testing'=> true,
        ];
        
        // Menyiapkan header permintaan API
        $header = [
            'Content-Type: application/json',
        ];

        // Melakukan permintaan API menggunakan metode connect() (asumsikan sudah didefinisikan sebelumnya)
        $response = self::connect($baseUrl, $data, $header);

        return $response;
    }

    public static function acak_nomor($length){
        $str = "";
        $karakter = array_merge(range('0','9'));
        $max_karakter = count($karakter) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max_karakter);
            $str .= $karakter[$rand];
        }
        return $str;
    }

    public static function getStatus(array $request){
        $id_user = $request['id_user'];
        $invoice = $request['invoice'];
    
        return OrderPrepaid::where('user_id', $id_user)
                           ->where('invoice', $invoice)
                           ->firstOrFail();
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

    public static function handleCallback($order, $status, $messages, $note, $price)
    {
        switch ($status) {
            case 'Success':
                $order->update([
                    'desc' => $messages,
                    'status' => $status,
                    'sn' => $note,
                ]);
                return true;

            case 'Fail':
                $order->update([
                    'desc' => $messages,
                    'status' => $status,
                    'sn' => $note,
                    'refund' => 1,
                ]);
                // Perform the balance update logic here
                User::where('id', $order->user_id)->update([
                    'balance' => $order->user->balance + $price,
                ]);
                return true;

            case 'Pending':
                $order->update([
                    'desc' => $messages,
                    'status' => $status,
                    'sn' => $note,
                ]);
                return true;

            default:
                LogTrx::created([
                    'log' => "Unrecognized payment status: ".$status,
                ]);
                return false;
        }
    }
}
