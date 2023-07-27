<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function generateCustomToken()
    {
        $token = base64_encode(random_bytes(40)); // Menghasilkan token acak sepanjang 40 byte dan di-encode dengan base64

        return $token;
    }

}
