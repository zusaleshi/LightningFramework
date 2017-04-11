<?php namespace Lightning\System;
if(! defined('framework_name')) exit('No direct script access allowed');

use Lightning\Exception\LFException;

class Core
{
	//object storage
	private static $_storage = array();

	//configuartion storage
	private static $_config = array();

	//event handler storage
	private static $_event = array();

	//route map storage
	private static $_route = array();

	//singleton
	private static $_instance = null;


	public function mount_DI($di)
	{
		if(isset($di->storage)) {
			self::$_storage = array_merge(self::$_storage, $di->storage);
		}

		if(isset($di->config)) {
			self::$_config = config_merge(self::$_config, $di->config);
		}

		if(isset($di->route)) {
			self::$_route = $di->route;
		}

		if(isset($di->event)) {
			self::$_event = array_merge_recursive(self::$_event, $di->event);
		}
	}


	public function getInstance()
	{
		if(!isset(self::$_instance)) self::$_instance = new self();
		return self::$_instance;
	}


	public function __get($key)
	{
		if(empty(self::$_storage[$key])) {
			throw new LFException("[Core] Cannot find a Object: {$key}");
		} else {
			return $this->return_object($key);
		}
	}


	public function __set($key, $value)
	{
		if(!empty(self::$_storage[$key]))
			throw new LFException("[Core] Stored Object:{$key} can not be overwrited.");
	}


	public function get_config()
	{
		$params = func_get_args();
		$result = array();
		foreach($params as $key) {
			if(empty(self::$_config[$key])) {
				return False;
			} else {
				$result = array_merge($result, self::$_config[$key]);
			}
		}
		return $result;
	}


	public function get_event()
	{
		return self::$_event;
	}


	public function get_route()
	{
		return self::$_route;
	}


	public function return_object($key)
	{
		if(is_closure(self::$_storage[$key])) {
			self::$_storage[$key] = call_user_func(self::$_storage[$key]);
		}
		return self::$_storage[$key];
	}
}