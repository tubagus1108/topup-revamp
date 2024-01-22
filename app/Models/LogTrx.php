<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTrx extends Model
{
    use HasFactory;
    protected $table = 'log_trx';
    protected $guarded = [];

    protected $fillable = [
        'log',
    ];

    public static function createLog(array $request){
        $log = new LogTrx([
            'log' => $request['log'],
        ]);
        
        $log->save();

        return $log;
    }
}
