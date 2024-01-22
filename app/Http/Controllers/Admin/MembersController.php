<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class MembersController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware('check.login');
        $this->user = new User();

    }

    public function index(){
        return view('admin.member');
    }

    public function store(CreateUserRequest $request){
        try {
            $user = User::createNewUser($request->all());
            
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully created user!',
                'data' => $user,
            ], HttpResponse::HTTP_CREATED);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
