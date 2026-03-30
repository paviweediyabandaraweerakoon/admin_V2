<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public function sendWarning($error, $errorMessages = [], $code = 200, $params = [])
    {
        $response = [
            'success' => true,
            'message' => $error,
            'warning' => true,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        if (!empty($params)) {
            $response['params'] = $params;
        }

        return response()->json($response, $code);
    }

    function reportFileName($string, $fileType, $prefix = null, $suffix = null)
    {
        $string = str_replace(' ', '_', $string);
        $string = preg_replace('/[^A-Za-z0-9\-]/', '_', $string);
        $string = preg_replace('/-+/', '_', $string);
        $string = strtolower($string);
        if (!empty($prefix)) {
            $string = $prefix . '_' . $string;
        }
        if (!empty($suffix)) {
            $string = $string . '_' . $suffix;
        }

        return $string . $fileType;
    }
}
