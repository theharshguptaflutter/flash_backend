<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Site Config
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	

	'dpo' => [
		'paygate_id'  	 		=> env('PAYGATE_ID'),		
		'paygate_secret' 		=> env('PAYGATE_SECRET'),
		'paygate_initiate_url' 	=> env('PAYGATE_INITIATE_URL'),
		'paygate_query_url' 	=> env('PAYGATE_QUERY_URL'),
	]
];