<?php

return array(
	'default' => array(

		/*
		|--------------------------------------------------------------------------
		| PHPCas Hostname
		|--------------------------------------------------------------------------
		|
		| Laravel uses a flexible driver-based system to handle authentication.
		| You are free to register your own drivers using the Auth::extend
		| method. Of course, a few great drivers are provided out of
		| box to handle basic authentication simply and easily.
		|
		| Exemple: 'cas.myuniv.edu'.
		|
		*/

		'cas_hostname' => 'cas.domain.fr',

		/*
		|--------------------------------------------------------------------------
		| Authentication Username
		|--------------------------------------------------------------------------
		|
		| Here you may specify the database column that should be considered the
		| "username" for your users. Typically, this will either be "username"
		| or "email". Of course, you're free to change the value to anything.
		|
		*/

		'cas_proxy' => false,

		/*
		|--------------------------------------------------------------------------
		| Authentication Password
		|--------------------------------------------------------------------------
		|
		| Here you may specify the database column that should be considered the
		| "password" for your users. Typically, this will be "password" but, 
		| again, you're free to change the value to anything you see fit.
		|
		*/

		'cas_port' => 443,

		/*
		|--------------------------------------------------------------------------
		| Authentication Model
		|--------------------------------------------------------------------------
		|
		| When using the "eloquent" authentication driver, you may specify the
		| model that should be considered the "User" model. This model will
		| be used to authenticate and load the users of your application.
		|
		*/

		'cas_uri' => '',

		/*
		|--------------------------------------------------------------------------
		| Authentication Table
		|--------------------------------------------------------------------------
		|
		| When using the "fluent" authentication driver, the database table used
		| to load users may be specified here. This table will be used in by
		| the fluent query builder to authenticate and load your users.
		|
		*/

		'cas_validation' => '',
		'cas_cert' => '/path/to/cert/file',
		'cas_login_url' => '',
		'cas_logout_url' => 'https://cas.domain.fr/logout?service=%s',
	)
);
