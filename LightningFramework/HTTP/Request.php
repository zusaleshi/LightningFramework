<?php namespace Lightning\HTTP;
if(! defined('framework_name')) exit('No direct script access allowed');

use Lightning\System\Core;

class Request extends Core
{
	private $_data = array();

	public function __construct()
	{
		foreach(array('GET' => $_GET, 'POST' => $_POST, 'REQUEST' => $_REQUEST, 'SERVER' => $_SERVER) as $name => $item) {
			$this->_data[$name] = array();
			foreach($item as $key => $val) {
				//$this->_data[$name][$key] = $val;
			}
			unset($item);
		}
	}

	public function getQuery($key = '', $filter = '')
	{
		if(empty($key)) {
			return $this->filter($this->_data['GET'], $filter);
		} elseif(isset($this->_data['GET'][$key])) {
			return $this->filter($this->_data['GET'][$key], $filter);
		} else {
			return False;
		}
	}

	public function getPost($key = '', $filter = '')
	{
		if(empty($key)) {
			return $this->filter($this->_data['POST'], $filter);
		} elseif(isset($this->_data['POST'][$key])) {
			return $this->filter($this->_data['POST'][$key], $filter);
		} else {
			return False;
		}
	}

	public function popQuery($key, $filter = '')
	{
		if(!isset($this->_data['GET'][$key])) {
			return False;
		} else {
			$ret = $this->getQuery($key, $filter);
			unset($this->_data['GET'][$key]);
			return $ret;
		} 
	}

	public function popPost($key, $filter = '')
	{
		
	}
}