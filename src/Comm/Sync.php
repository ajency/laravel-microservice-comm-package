<?php

namespace Ajency\Comm;

use GuzzleHttp\Client;

class Sync
{
    public static function call($microservice, $method, $params)
    {
        
        $client = new Client(config('service_comm.url.'$microservice) ); 
        $result = $client->request('POST','service_comm/listen',  [
            'json' => [
                'method' => $method,
                'params' => $params
            ],
        ]);
    }
}
