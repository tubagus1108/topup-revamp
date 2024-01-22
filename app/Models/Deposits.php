<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Deposits extends Model
{
    use HasFactory;

    protected $table = 'deposits';
    protected $gaurded = [];
    protected $amount;

    protected $fillable = [
        'user_id',
        'method_id',
        'payment_no',
        'amount',
        'fee',
        'total_amount',
        'status',
    ];
    protected $appends = ['qris', 'type'];

    public function method_deposit()
    {
        return $this->belongsTo(Payment::class, 'method_id');
    }

    public function getQrisAttribute()
    {
        return $this->method_deposit->image_qris ? Storage::disk('public')->url($this->method_deposit->image_qris) : null;
    }

    public function getTypeAttribute()
    {
        return $this->method_deposit->type ?? null;
    }

    public static function createdDeposit(array $request)
    {

        $payment_menthod = Payment::where('id', $request['method_id'])->first();
        if (!$payment_menthod) {
            return false;
        }

        if ($payment_menthod->type == 'qris') {
            $fee = config('services.qris.fee');
        } else {
            $fee = 0;
        }

        $amount = $request['amount'] + rand(1, 999);
        $total_fee = round($amount * $fee);
        $total_amount = $amount + $total_fee;

        $deposit = new Deposits([
            'user_id' => $request['user_id'],
            'method_id' => $request['method_id'],
            'payment_no' => $request['payment_no'],
            'amount' => $amount,
            'fee' => $total_fee,
            'total_amount' => $total_amount,
            'status' => $request['status']
        ]);

        $deposit->save();
        return $deposit;
    }

    public static function depositPending($user_id)
    {
        return Deposits::where('user_id', $user_id)->where('status', 'Pending')->first();
    }
}
