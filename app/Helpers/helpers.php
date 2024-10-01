<?php

if (!function_exists('successResponse')) {
    /**
     * Success Response Helper
     *
     * @param string $message
     * @param array $data
     * @param int|null $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    function successResponse($message, $data = [], $statusCode = null)
    {
        // Fetch the status code dynamically or default to 200
        $statusCode = $statusCode ?? \Illuminate\Http\Response::HTTP_OK;

        return response()->json([
            'status' => 'success',  // Add status here
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}

if (!function_exists('errorResponse')) {
    /**
     * Error Response Helper
     *
     * @param string $message
     * @param int|null $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    function errorResponse($message, $statusCode = null)
    {
        // Fetch the status code dynamically or default to 400
        $statusCode = $statusCode ?? \Illuminate\Http\Response::HTTP_BAD_REQUEST;

        return response()->json([
            'status' => 'error',  // Add status here
            'message' => $message
        ], $statusCode);
    }
}
