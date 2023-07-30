<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Services;
use Illuminate\Http\Request;

class RestApiController extends Controller
{
    //Get User Profile
    public function profile(Request $request){
        $user = $request->get('user');
        return response()->json(['status' => 'success', 'message' => 'Success get profile','data' => $user]);
    }

    public function product(Request $request){
        $user = $request->get('user')->role;        
        $data = Services::getService($user);
        return response()->json(['status' => 'success','message' => 'Success get product-list','data' => $data]);
    }
}
