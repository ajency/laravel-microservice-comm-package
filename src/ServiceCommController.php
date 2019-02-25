<?php

namespace Ajency\ServiceComm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceCommController extends Controller
{

    public function __construct()
    {
        //
    }

    public function serviceCommListen(Request $request)
    {
        $a = \Ajency\ServiceComm\Comm\Sync::listen($request->method, $request->params);
        return response()->json($a);
    }

}
