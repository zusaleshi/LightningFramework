<?php namespace Lightning\Event;
if(! defined('framework_name')) exit('No direct script access allowed');


class EventDI
{
	private $handler;

	public function __construct()
	{
		$this->handler = array();
	}

	public function on($event_name, $function, $prepend = False)
	{
		if(!isset($this->handler[$event_name]))
				$this->handler[$event_name] = array();

		if($prepend) {
			array_unshift($this->handler[$event_name], $function);
		} else {
			$this->handler[$event_name][] = $function;
		}
	}

	public function get_storage()
	{
		return $this->handler;
	}
}