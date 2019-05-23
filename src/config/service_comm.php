<?php

return [
	'auth_token' => env("SERVICE_COMM_AUTH",""),
	'url' => [
		'service_name' => 'service_url'
	],
	'mapping' => [
		'api_name' => [
			'model' => 'model_name',
			'function' => 'function_name',
		],
	],
	'async_provider' => 'sns',
	'sns' => [
		'client' => [
			'id' => env('AWS_ACCOUNT_ID'),
			'credentials' => [
				'key' => env('AWS_ACCESS_KEY'),
				'secret' => env('AWS_ACCESS_SECRET')
			],
			'region'=> env('AWS_REGION'),
			'version' => 'latest'
		],
		'prefix' => str_slug(env(APP_ENV)),
		'topics' => [],
	],
];