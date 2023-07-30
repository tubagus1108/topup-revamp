<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetServiceRequest;
use App\Models\Services;
use Illuminate\Http\Request;
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
            ],HttpResponse::HTTP_ACCEPTED);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
