<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public  $integration_key = '511afb39-2031-4a08-ae6e-17766dfc1d0e';
    public  $integration_id = '7483';

    public function jsonError($statusCode = 500, $message = "Unexpected Error")
    {

        return response()->json([
            "success" => false,
            "message" => $message
        ], $statusCode);
    }

    public function jsonSuccess($statusCode = 200, $message = "Request Successful")
    {
        return response()->json([
            "success" => true,
            "message" => $message
        ], $statusCode);
    }
}
