<?php

$di = require('../Vendor/LightningFramework/Bootstrap.php');
$di->set_application_path(dirname(dirname(__FILE__)) . '/Application');

try {

	$app = new Lightning\MVC\WebApplication($di);
	echo $app->handle();

} catch(Exception $e) {

	show_error($e->getMessage());

}



?>

