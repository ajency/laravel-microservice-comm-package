<?php

namespace Ajency\ServiceComm\Comm;

use GuzzleHttp\Client;

class Sync 
{
    public static function call($microservice, $method, $params)
    {
        
        $client = new Client(['base_uri' => config('service_comm.url.'.$microservice) ]); 
        $result = $client->request('POST','service_comm/listen',  [
            'json' => [
                'method' => $method,
                'params' => $params
            ],
        ]);
        return json_decode($result->getBody());
    }

    public static function listen($method,$params){
        //authorization code here

        $functionDetails = config('service_comm.mapping.'.$method);
        if(is_null($functionDetails)) throw new \Exception("Undefined method {$method}", 1);
        
        return $functionDetails['model']::$functionDetails['function']($params);
        
    }
}
