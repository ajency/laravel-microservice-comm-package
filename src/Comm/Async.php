<?php

namespace Ajency\ServiceComm\Comm;
use Log;

/**
 * Class for asynchronous communication to an external service
 *
 * @package ServiceComm
 * @author Shashank Shetye Saudagar
 **/
class Async
{
	/**
	 * Do an async call to a provider
	 * @param string topic - Identifier for the message you are publishing
	 * @param array payload - payload of the message to be sent
	 * @param string provider - provider for the service to be used
	 * @return void | guzzle promise
	 * @author Shashank Shetye Saudagar
	 **/
	public static function call($topic,$payload,$provider = null, $returnPromise = true)
	{
		getEnvHelper();
		if(is_null($provider)){
			$provider = config('service_comm.async_provider');
		}
		Log::notice('Publishing to ' . $topic . ' with ' . $provider);
		switch ($provider) {
			case 'sns':
				return self::snsCall($topic,$payload,$returnPromise);
			default:
				Log::error('Provider '.$provider.' Not supported');
				break;
		}
	}

	/**
	 * Publish to AWS SNS asynchronously
	 * @param string topic - Identifier for the message you are publishing
	 * @param array payload - payload of the message to be sent
	 * @return guzzle promise
	 * @author Shashank Shetye Saudagar
	 **/
	protected static function snsCall($topic, $payload, $returnPromise)
	{
		$sns = SNS::createInstance();
		if($returnPromise)
			return $sns->publishAsync($topic,$payload);
		else 
			return $sns->publish($topic,$payload);
	}
} // END class 