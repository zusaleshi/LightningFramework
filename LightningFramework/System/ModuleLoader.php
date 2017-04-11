<?php namespace Lightning\System;
if(! defined('framework_name')) exit('No direct script access allowed');

use Lightning\System\Core;
use Lightning\System\DI;

class ModuleLoader extends Core
{
	private $module_map = array();
	private $module_config = null;

	public function __construct()
	{
		$this->module_map = require(APP_PATH . '/Config.Global/module.php');
	}

	public function load_module($domain, $url)
	{
		foreach($this->module_map as $key => $obj) {
			if(True !== custom_match($obj['domain'], $domain) || strpos($url, $obj['route']) !== 0)
				continue;
			$this->module_config = $obj;
			break;
		}
		if(is_null($this->module_config)) show_404('The page does not exist on the server');

		//set error display
		if($this->module_config['environment'] === 'production') {

			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
			ini_set('display_errors', 0);

		} elseif($this->module_config['environment'] === 'development') {

			error_reporting(-1);
			ini_set('display_errors', 1);

		}

		$this->register_class_loader();
	}

	private function register_class_loader()
	{
		$module_path = $this->module_config['src'];
		$namespace = $this->module_config['namespace'];

		spl_autoload_register(function($class) use ($namespace, $module_path){
			$class_arr = explode('\\', $class);
			if($class_arr[0] !== $namespace) return;

			array_shift($class_arr);
			$file_path = $module_path . implode('/', $class_arr) . '.php';
			if(!file_exists($file_path)) {
				throw new LFExceptioin("Module_Autoload: {$class}, File does not exist. {$file_path}");
			} else {
				require($file_path);
				if(!class_exists($class) && !interface_exists($class)) 
					throw new LFExceptioin("Module_Autoload: {$class} does not exist");
			}
		});
	}

	public function build_module_DI()
	{
		$di = new DI();

		foreach(array('config', 'event', 'route') as $item) {
			$file_path = "{$this->module_config['path']}/Config/{$item}.php";
			if(file_exists($file_path)) $di->load($item, require($file_path));
		}

		$load_path = "{$this->module_config['path']}/Config/load.php";
		if(file_exists($load_path)) $di->set(require($load_path));

		return $di;
	}
}