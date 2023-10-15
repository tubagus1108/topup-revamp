<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposits extends Model
{
    use HasFactory;

    protected $table = 'deposits';
    protected $gaurded = [];

    protected $fillable = [
        'user_id',
        'method_id',
        'payment_no',
        'amount',
        'status',
    ];

    public function method_deposit()
    {
        return $this->belongsTo(Payment::class, 'method_id');
    }

    public function getMethodDepositNameAttribute()
    {
        return $this->method_deposit->name ?? null;
    }

    public static function createdDeposit(array $request)
    {
        $deposit = new Deposits([
            'user_id' => $request['user_id'],
            'method_id' => $request['method_id'],
            'payment_no' => $request['payment_no'],
            'amount' => $request['amount'] + rand(1, 999),
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
