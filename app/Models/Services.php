<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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

    protected $hidden = [
        'category_id',
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

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function getCategoryNameAttribute(){
        return $this->category->name ?? null;
    }

    public static function getServiceDatatable($start, $length, $column, $order)
    {
        $services = Services::with('category')
                    ->offset($start)
                    ->limit($length)
                    ->orderBy($column, $order)
                    ->get();

        // // Ubah setiap layanan menjadi array dan ubah category_id menjadi nama kategori
        $transformedServices = $services->transform(function ($service) {
            $serviceArray = $service->toArray();
            $serviceArray['category'] = $service->category->code;
            return $serviceArray;
        });

        return $transformedServices;
    }

    public static function getService($role)
    {
        $services = Services::with('category')->get();

        $transformedServices = $services->map(function ($service) use ($role) {
            return self::transformService($service, $role);
        });

        return $transformedServices;
    }

    private static function transformService($service, $role)
    {
        $serviceArray = $service->toArray();
        $serviceArray['category'] = $service->category->code;

        // Set price based on role
        $rolePriceMapping = self::getRolePriceMapping();
        if(isset($rolePriceMapping[$role])){
            $serviceArray['price'] = $serviceArray[$rolePriceMapping[$role]];
        }

        self::removeUnnecessaryFields($serviceArray);
        return $serviceArray;
    }

    private static function getRolePriceMapping()
    {
        // consider these values to be constants or from configuration
        return [
            'Admin' => 'price',
            'Member' => 'price_member',
            'Platinum' => 'price_platinum',
            'Gold' => 'price_gold'
        ];
    }

    private static function removeUnnecessaryFields(&$serviceArray)
    {
        $unnecessaryFields = ['id', 'price_member', 'price_platinum', 'price_gold', 'profit', 'profit_member', 'profit_gold', 
                            'profit_member', 'profit_platinum', 'provider', 'product_logo', 'created_at', 'updated_at', 'deleted_at'];

        foreach($unnecessaryFields as $field){
            unset($serviceArray[$field]);
        }
    }

}
