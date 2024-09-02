<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiResponses extends Controller
{
    public static function send($message, $code = 400, $error = null, $data = null){

        $response = [
            'success' => $code >= 200 && $code <= 226 ? true : false,
            'message' => $message,
            'data' => $data,
            'errors' => $error
        ];


        return response()->json($response, $code);

    }
}
