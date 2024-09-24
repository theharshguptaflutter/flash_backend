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

	

	'admin' => [
		'mask'  => env('ADMIN_MASK', 'admin'),		
		'title' => env('APP_NAME', 'Site').' Administrtion',		
		'name'  => 'Administrtor',		
		'role'  => 'admin',
		'email' => 'flash@gmail.com',
		'search' => [
			'proximity' => [ 
				'OR' => 'Match Any Parameter','AND'=>'Match All Parameters' 
			]
		],
	],
	'steps' => [
		'status' => ['pending', 'success', 'cancle']
	]	
];