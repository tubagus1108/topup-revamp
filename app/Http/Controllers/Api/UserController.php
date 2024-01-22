<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\GetUsersRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class UserController extends Controller
{
    public function getUsersDatatable(GetUsersRequest $request)
    {
        try {
            $users = User::getUsersDatatable(
                $request->get('start'),
                $request->get('length'),
                $request->get('order')[0]['column'],
                $request->get('order')[0]['dir']
            );
        
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => User::count(),
                'recordsFiltered' => User::count(),
                'data' => $users,
            ],HttpResponse::HTTP_ACCEPTED);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createUser(CreateUserRequest $request)
    {
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

    public function detailUser($id)
    {
        try {
            $user = User::getDetails($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Success get detail user',
                'data' => $user
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteUser($id)
    {
        try {
            User::deleteUser($id);

            return response()->json([
                'status' => 'success',
                'message' => 'User has been soft deleted.',
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function editUser(EditUserRequest $request, $id)
    {
        try {
            $user = User::editUser($id, $request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'User has been updated.',
                'data' => $user
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
