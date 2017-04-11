<?php namespace Lightning\System;
if(! defined('framework_name')) exit('No direct script access allowed');

use Lightning\Exception\LFException;

class DI
{
	public $path;
	public $storage = array();
	public $event 	= array();
	public $route 	= array();
	public $config 	= array();


	public function set()
	{
		$params = func_get_args();

		if(is_array($params[0])) {
			$override = (count($params) == 2) ? (bool) $params[1] : False;
			foreach($params[0] as $key => $obj) {
				if(isset($this->storage[$key]) and !$override) {
					throw new LFException("Error in DI->set(): {$key} already existed");
				} else {
					$this->storage[$key] = is_closure($obj) 
												? $obj
												: function() use ($obj) {
													return new $obj();
												};
				}
			}
		} else {
			$override = (count($params) == 3) ? $params[2] : False;

			$key = $params[0];
			$obj = $params[1];

			if(isset($this->storage[$key]) and !$override) {
				throw new LFException("Error in DI->set(): {$key} already existed");
			} else {
				$this->storage[$key] = $obj;
			}
		}
	}


	public function load($key, $load_data) {
		if(!isset($this->$key)) throw new LFException("Error in DI->load(): {$key} is not defined");

		if($key == 'event') {
			$this->$key[] = $load_data;
		} else {
			$this->$key = array_merge($this->$key, $load_data);
		}
	}


	public function set_application_path($path)
	{
		$this->path = $path;
	}


}