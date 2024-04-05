<?php
namespace App\Trait;

trait ApiResponse
{
    protected function successResponse($code,$data,$message=null)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ],$code);
    }
    protected function errorResponse($code,$message=null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ],$code);
    }
}
