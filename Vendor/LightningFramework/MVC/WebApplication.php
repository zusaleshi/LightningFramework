<?php namespace Lightning\MVC;
if(! defined('framework_name')) exit('No direct script access allowed');


use Lightning\System\Core;
use Lightning\System\ModuleLoader;
use Lightning\HTTP\Router;
use Lightning\Exception\LFException;

class WebApplication extends Core
{
	private $_url = null;
	private $_controller = null;
	private $_action = null;
	private $_params = null;
	private $_page = null;
	private $_response = null;

	public function __construct($di)
	{
		$di->set('app', $this);
		define('APP_PATH', $di->path);

		//load global config
		foreach(array('config', 'event') as $item) {
			$file_path = APP_PATH . "/Config.Global/{$item}.php";
			if(file_exists($file_path)) $di->load($item, require($file_path));
		}
		$load_path = APP_PATH . '/Config.Global/load.php';
		if(file_exists($load_path)) $di->set(require($load_path));

		$this->mount_DI($di);
		$this->event
				->mount($this->get_event())
				->emit('EVENT_FRAMEWORK_START');
	}


	public function handle()
	{
		if(!is_null($this->_page)) return $this->_page;

		$module_loader = new ModuleLoader();
		$module_loader->load_module($this->request->getDomain(), $this->request->getUrl());
		$this->mount_DI($module_loader->build_module_DI());
		$this->event->mount($this->get_event())
					->emit('EVENT_MODULE_LOADED');
		
		if(  is_null($this->_page)
				&& (is_null($this->_response))
				&& (is_null($this->_controller) 
					||	is_null($this->_action) 
					||	is_null($this->_params))  ) {
			if(is_null($this->_url)) $this->_url = $module_loader->getModuleUrl();
			$this->event->emit('EVENT_PARSE_URL');
			$arr = Router::parseUrl($this->_url);

			$this->_controller = $arr['controller'];
			$this->_action = $arr['action'];
			$this->_params = $arr['params'];
		}

		return $this->dispatch();
	}


	public function dispatch()
	{
		$this->event->emit('EVENT_DISPATCH');
		if(is_null($this->_page)) {
			ob_start();
			$controller = $this->_controller;
			$action = $this->_action;
			$params = $this->_params;

			$dispatcher = new $controller();
			if(method_exists($dispatcher, $action)) {
				$this->_response = call_user_func_array(array($dispatcher, $action), $params);
			} else {
				throw new LFException("Dispatch(): <i>{$action}</i> does not exist in <b>{$controller}</b>");
			}
			$this->_page = ob_get_contents();
			ob_end_clean();
		}
		$this->event->emit('EVENT_GET_PAGE');
		return $this->_page;
	}


	public function get($type)
	{
		$type = "_{$type}";
		return $this->type;
	}
	

	public function set($type, $data)
	{
		$type = "_{$type}";
		$this->$type = $data;
		return $this;
	}
}