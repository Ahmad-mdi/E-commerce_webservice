<?php
namespace App\Traits;
trait ApiResponse {
    protected function successResponse ($code,$data,$message=null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
           'status' => 'success',
           'data' => $data,
           'message' => $message,
        ]);
    }

    protected function errorResponse($code,$message)
    {
        return response()->json([
            'status' => 'failed',
            'message' => $message,
        ]);
    }
}
