<?php 
namespace Lightning;

use Lightning\Exception\LightningException;
use Lightning\System\DI;

Class Bootstrap
{
	public function __construct()
	{
		define('framework_name', 'LightningFramework');

		define('framework_path', 	dirname(__FILE__) . '/');
		define('system_path', 		framework_path . 'System/');

		require(framework_path . 'Helper\Functions.php');
		require(framework_path . 'Helper\Constant.php');

		set_exception_handler(function($e) {
			$code 	= $e->getCode();
			$file 	= $e->getFile();
			$str  	= $e->getMessage();
			$line 	= $e->getLine();
			$msg = "[{$code}]: {$str} in {$file} on line {$line}\r\n";
			show_error($msg);
		});

		set_error_handler(function($errno, $errstr, $errfile, $errline){
			$errfile = basename($errfile);
			$msg = "[{$errno}]: {$errstr} in {$errfile} on line {$errline}";
			show_error($msg);
		});
	}

	public function build_DI()
	{
		$di = new DI();

		$di->set('request', 	new \Lightning\HTTP\Request());
		$di->set('router', 		new \Lightning\HTTP\Router());
		$di->set('event', 		new \Lightning\Event\EventManager());

		return $di;
	}
}