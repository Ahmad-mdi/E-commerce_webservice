<?php
namespace App\Traits;
trait ApiResponse {
    protected function successResponse ($code,$data,$message=null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
           'status' => 'success',
            'message' => $message,
           'data' => $data,
        ]);
    }

    protected function errorResponse($code,$message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'failed',
            'message' => $message,
        ]);
    }
}
