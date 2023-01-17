<?php

namespace App\Http\Controllers;

trait HttpResponses
{
    /**
     * @param array $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'Request was successful',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @param array $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error($data, $message = null, $code)
    {
        return response()->json([
            'status' => 'Error was occurred',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}