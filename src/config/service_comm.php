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
];