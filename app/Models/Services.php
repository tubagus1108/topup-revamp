<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $gaurded = [];

    protected $fillable = [
        'category_id',
        'name',
        'sid',
        'price',
        'price_member',
        'price_platinum',
        'price_gold',
        'profit',
        'profit_member',
        'profit_platinum',
        'profit_gold',
        'notes',
        'status',
        'provider',
    ];

    public static function createService(array $request)
    {
        $services = new Services([
            'category_id' => $request['category_id'],
            'name' => $request['name'],
            'sid' => $request['sid'],
            'price' => $request['price'],
            'price_member' => $request['price_member'],
            'price_platinum' => $request['price_platinum'],
            'price_gold' => $request['price_gold'],
            'profit' => $request['profit'],
            'profit_member' => $request['profit_member'],
            'profit_platinum' => $request['profit_platinum'],
            'profit_gold' => $request['profit_gold'],
            'notes' => $request['notes'],
            'status' => $request['status'],
            'provider' => $request['provider'],
        ]);
        
        $services->save();

        return $services;
    }

    public static function editService($request, $id) {
        $service = Services::findOrFail($id);
        $service->fill($request);
        $service->save();
    
        return $service;
    }
    
}
