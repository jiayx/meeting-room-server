<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * @param array|null $data
     * @param string $code
     * @param string $message
     * @return \Illuminate\Http\Response
     */
    public function response($data = null, $code = '200', $message = 'success')
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function success($message = 'success', $code = '200', $data = null)
    {
        return $this->response($data, $code, $message);
    }

    public function error($code = '400', $message = 'error')
    {
        return $this->response(null, $code, $message);
    }
}
