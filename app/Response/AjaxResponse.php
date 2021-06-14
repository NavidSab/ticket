<?php

namespace App\Response;

class AjaxResponse
{

    public static function success($data = [], $responseCode)
    {
        return response()->json([
            'success'=>true,
            'data' => $data
        ], $responseCode);
    }

    public static function error($errors = [], $responseCode)
    {
        return response()->json([
            'success'=>false,
            'errors' => [
                'message'=>$errors
            ]
        ], $responseCode);
    }
}
