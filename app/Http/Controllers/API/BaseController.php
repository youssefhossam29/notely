<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    //
    public function SendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];
        return response()->json($response, 200);
    }

    public function SendError($error, $errorMessage=[], $code = 404)
    {
        $response = [
            'success' => false,
            'error' => $error,
            'message' => (empty($errorMessage)) ? null: $errorMessage,
        ];
        return response()->json($response, $code);
    }
}
