<?php if(! defined('framework_name')) exit('No direct script access allowed');

return array(

	'Blog' => array(
				'domain'		=> 'localhost',
				'route'			=> '/',
				'src'			=> APP_PATH . '/module/Blog',
				'namespace'		=> 'Blog',
				'environment'	=> 'development',
			),

	'OneCity' => array(
				'domain' 		=> 'www.onecity.com',
				'route'	 		=> '/',
				'src'	 		=> APP_PATH . '/module/OneCity',
				'namespace'		=> 'OneCity',
				'environment'	=> 'production',
			),
);