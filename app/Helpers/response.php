<?php

   /**
     * Generate a custom JSON response.
     *
     * @param int $statusCode HTTP status code
     * @param array $data Response data
     * @param string|null $message Optional response message
     * @return \Illuminate\Http\JsonResponse
     */
    function customResponse(int $statusCode,  $data = [], string $message = null)
    {
        $status = in_array($statusCode, [200, 201, 202, 205]);
        
        // Default messages for specific status codes
        $statusMessages = [
            201 => trans('messages.Successfully Done'),
            205 => trans('messages.Successfully Done'),
            202 => trans('messages.Successfully Done'),
            204 => trans('messages.Successfully Done'),
            500 => trans('messages.Something went wrong'),
            310 => trans('messages.Email Not Verify'),
            401 => trans('messages.Un Authorized'),
            403 => trans('messages.User does not have the necessary access rights'),
            404 => trans('messages.Not Found')
        ];

        // Set default message based on status code if not provided
        if (is_null($message) && isset($statusMessages[$statusCode])) {
            $message = $statusMessages[$statusCode];
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Generate a success response.
     *
     * @param int $num Status code suffix
     * @param array|null $data Optional response data
     * @param string|null $message Optional response message
     * @return \Illuminate\Http\JsonResponse
     */
    function successResponse(int $num,  $data = null, string $message = null)
    {
        return customResponse(200 + $num, $data, $message);
    }

    /**
     * Generate a client error response.
     *
     * @param int $num Status code suffix
     * @param string|null $message Optional error message
     * @return \Illuminate\Http\JsonResponse
     */
    function clientError(int $num, string $message = null)
    {
        return customResponse(400 + $num, [], $message);
    }

    /**
     * Generate a server error response.
     *
     * @param int $num Status code suffix
     * @return \Illuminate\Http\JsonResponse
     */
    function serverError(int $num)
    {
        return customResponse(500 + $num);
    }

    /**
     * Extract data from a response collection.
     *
     * @param \Illuminate\Http\Response $dataCollection
     * @return array
     */
    function getDataResponse($dataCollection)
    {
        return $dataCollection->response()->getData(true);
    }
