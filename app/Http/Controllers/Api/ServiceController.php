<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetServiceRequest;
use App\Models\Category;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ServiceController extends Controller
{
    public function getServiceDatatable(GetServiceRequest $request)
    {
        try {
            $services = Services::getServiceDatatable(
                $request->get('start'),
                $request->get('length'),
                $request->get('order')[0]['column'],
                $request->get('order')[0]['dir']
            );

            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => Services::count(),
                'recordsFiltered' => Services::count(),
                'data' => $services,
            ], HttpResponse::HTTP_ACCEPTED);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function layananDetail($type, $code)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
        $getCategory = Category::where('code', $code)->where('type', $type)->where('status', 'active')->first();
        if ($getCategory) {
            $product = Services::getServiceByCode($user->role, $getCategory->id);
            return response()->json(['status' => 'success', 'message' => 'Success get product', 'data' => $product]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Category not found'], 404);
        }
    }

    public function serviceType($type)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
        $getCategory = Category::where('type', $type)->where('status', 'active')->get();
        return response()->json(['status' => 'success', 'message' => 'Success get sevice', 'data' => $getCategory]);
    }
}
