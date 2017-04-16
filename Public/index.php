<?php

require('../vendor/autoload.php');


try {

	$bootstrap = new Lightning\Bootstrap();
	$di = $bootstrap->build_DI();
	throw new Exception("Error Processing Request", 1);
	

} catch(Exception $e) {

	show_error($e->getMessage());

}



?>

