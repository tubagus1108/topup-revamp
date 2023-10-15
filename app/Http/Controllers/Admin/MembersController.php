<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use DataTables;

class MembersController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware('check.login');
        $this->user = new User();
    }

    public function index()
    {
        return view('admin.member');
    }

    public function store(CreateUserRequest $request)
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

    public function datatableMembers()
    {
        $data = User::where('role', '<>', 'Admin')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $delete_link = "'" . url('books-management/sub-category-delete/' . $data['id']) . "'";
                $delete_message = "'This cannot be undo'";

                // $delete = '<button onclick="confirm_me(' . $delete_message . ',' . $delete_link . ')" class="btn btn-danger p-1 text-white"> <i class="fa fa-trash"> </i> </button>';
                $delete = '<a href="javascript:;" onclick="confirm_me(' . $delete_message . ',' . $delete_link . ')" class="btn btn-info"><i class="fa fa-qrcode"></i>Delete</a>';
                return $delete;
            })
            ->addColumn('created_at', function ($data) {
                return Carbon::parse($data['created_at'])->format('F d, y');
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
