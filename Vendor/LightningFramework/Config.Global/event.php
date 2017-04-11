<?php if(! defined('framework_name')) exit('No direct script access allowed');
$event_DI = new Lightning\Event\EventDI();


$event_DI->on('EVENT_FRAMEWORK_START', function($app){
	header('X-Powered-By: LightningFramework');
});


return $event_DI;