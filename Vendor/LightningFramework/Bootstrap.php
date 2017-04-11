<?php

use Lightning\Exception\LFException;
use Lightning\System\DI;

header('Content-Type: text/html; charset=utf-8');
define('framework_name', 'LightningFramework');
define('framework_path', dirname(__FILE__) . '/');


require(framework_path . 'Helper\Functions.php');
require(framework_path . 'Helper\Constant.php');

spl_autoload_register(function($class){
	$class_arr = explode('\\', $class);
	if($class_arr[0] != 'Lightning') return;

	array_shift($class_arr);
	$file_path  = framework_path . implode('/', $class_arr) . '.php';
	if(  !file_exists($file_path)  ) {
		show_error("Autoloading: {$class} File does not exist {$file_path}");
	} else {
		require($file_path);
		if(!(class_exists($class) or interface_exists($class)))
			show_error("Autoloading: {$class} does not exist");
	}
});

set_exception_handler(function($e){
	show_error($e->getMessage());
});

set_error_handler(function($errno, $errstr, $errfile, $errline){
	$errfile = basename($errfile);
	$msg = "{$errno}: {$errstr} in {$errfile} on line {$errline}";
	show_error($msg);
});

$di = new DI();
$di->set('request', new Lightning\HTTP\Request());

$di->set('event', new Lightning\Event\EventManager());

$di->set('session', function(){
	$sess = new Lightning\Session\Session();
	$sess->start();
	return $sess;
});

return $di;