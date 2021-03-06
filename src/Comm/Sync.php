<?php

namespace Ajency\ServiceComm\Comm;

use GuzzleHttp\Client;
use Log;
use Exception;

class Sync
{
    public static function call($microservice, $method, $params)
    {
        getEnvHelper();
        Log::notice('Calling ' . $microservice . ' at ' . config('service_comm.url.' . $microservice));
        $start  = microtime(true);
        $client = new Client(['base_uri' => config('service_comm.url.' . $microservice)]);
        try{
            $result = $client->request('POST', 'service_comm/listen', [
                'json' => [
                    'method' => $method,
                    'params' => $params,
                    'auth' => config('service_comm.auth_token'),
                ],
            ]);
            $message = 'Call to ' . $microservice . ' method=' . $method . 'with params ' . json_encode($params) . 'took time ' . round(microtime(true) - $start, 3) . ' seconds and resulted in status '.$result->getStatusCode().' content' . $result->getBody();
            Log::notice($message);
            if(200 != $result->getStatusCode()) throw new Exception($messsage);
        }catch (Exception $e){
            Log::error($e->getMessage());
            return [];
        }
        return json_decode($result->getBody(), true);
    }

    public static function listen($method, $params)
    {
        //authorization code here


        $functionDetails = config('service_comm.mapping.' . $method);
        if (is_null($functionDetails)) {
            throw new \Exception("Undefined method {$method}", 1);
        }

        Log::debug($functionDetails['function']);
        $f = $functionDetails['function'];
        return $functionDetails['model']::$f($params);
    }
}
