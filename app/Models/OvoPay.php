<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvoPay extends Model
{
    use HasFactory;
    protected $table = 'ovopay';
    protected $gaurded = [];

    protected $fillable = [
        'phone',
        'token',
    ];
}
