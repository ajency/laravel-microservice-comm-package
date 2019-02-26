<?php

namespace Ajency\ServiceComm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

class ServiceCommController extends Controller
{

    public function __construct()
    {
        //
    }

    public function serviceCommListen(Request $request)
    {
    	if($request->auth != config('service_comm.auth_token')){
            Log::notice('authentication failure for method '.$request->method);
    		return response()->json(["message" => "incorrect auth token"],403);
    	}
        $a = \Ajency\ServiceComm\Comm\Sync::listen($request->method, $request->params);
        return response()->json($a);
    }

}
