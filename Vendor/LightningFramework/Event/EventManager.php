<?php namespace Lightning\Event;
if(! defined('framework_name')) exit('No direct script access allowed');

use Lightning\Exception\LFException;
use Lightning\System\Core;

class EventManager extends Core
{
	private $event_handler = array();

	public function __construct(){}

	public function emit($event_name)
	{
        $res = True;
		if(isset($this->event_handler[$event_name])) {
            foreach($this->event_handler[$event_name] as $function) {
                $r = call_user_func_array($function, array($this->app));
                $res &= is_bool($r) ? $r : False;
            }
        }
        return $res;
	}

    public function mount(array $eventDI_array)
    {
        foreach($eventDI_array as $item) {
            $this->event_handler = array_merge_recursive($this->event_handler, $item->get_storage());
        }
        return $this;
    }

    public function get_handlers($event_name)
    {
    	return (isset($this->event_handler[$event_name]))
    		? $this->event_handler[$event_name]
    		: Null;
    }
}