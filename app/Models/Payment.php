<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';
    protected $gaurded = [];

    protected $fillable = [
        'name',
        'image',
        'code',
        'desc',
        'type',
    ];

    public static function createdPayment(array $request){
        $payment = new Payment([
            'name' => $request['name'],
            'image' => $request['image'],
            'code' => $request['code'],
            'type' => $request['type']
        ]);
        
        $payment->save();

        return $payment;
    }
}
