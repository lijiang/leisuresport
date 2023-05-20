<?php

use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

function exceptionResponse($responseCode, $message)
{
    return new HttpResponseException(response()->json([
        'message' => $message,
        'status' => $responseCode
    ], $responseCode));
}

function exceptionWithErrorCodeResponse($httpResponseCode, $errorCode, $message)
{
    return new HttpResponseException(response()->json([
        'message' => $message,
        'status' => $errorCode
    ], $httpResponseCode));
}

function okHttpResponse($data, $pageInfo = null)
{
    $response = [
        'status' => Response::HTTP_OK,
        'data' => $data
    ];
    if ($pageInfo) {
        $response['pageInfo'] = $pageInfo;
    }
    return response($response, response::HTTP_OK);

}


