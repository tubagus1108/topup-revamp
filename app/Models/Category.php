<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';
    protected $gaurded = [];

    protected $fillable = [
        'name',
        'code',
        'brand',
        'status',
        'type',
    ];

    public static function createCategory(array $request)
    {
        $category = new Category([
            'name' => $request['name'],
            'code' => $request['code'],
            'brand' => $request['brand'],
            'status' => 'active',
            'type' => $request['type'],
        ]);
        
        $category->save();

        return $category;
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }
}
