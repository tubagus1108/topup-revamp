<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';
    protected $gaurded = [];

    protected $fillable = [
        'name',
        'image',
        'image_qris',
        'code',
        'desc',
        'type',
    ];

    public static function createdPayment(Request $request)
    {
        $payment = new Payment([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'type' => $request->input('type'),
            'desc' => $request->input('desc'),
        ]);


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_uploaded_path = $image->store('images', 'public');
            $payment['image'] = $image_uploaded_path;
        }

        if ($request->hasFile('image_qris')) {
            $image_qris = $request->file('image_qris');
            $image_qris_uploaded_path = $image_qris->store('images/qris', 'public');
            $payment['image_qris'] = $image_qris_uploaded_path;
        }

        $payment->save();

        return $payment;
    }
}
