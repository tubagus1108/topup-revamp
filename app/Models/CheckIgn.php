<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckIgn extends Model
{
    use HasFactory;

    protected $connection = 'db_read';
    protected $table = 'check_ign';
    protected $guarded = [];

    protected $dates = ['expired_at'];
}
