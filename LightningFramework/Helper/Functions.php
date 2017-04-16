<?php if(! defined('framework_name')) exit('No direct script access allowed');

use Lightning\Exception\LightningException;

function is_closure($obj)
{
	return is_object($obj) && ($obj instanceof Closure);
}

function config_merge($arr_a, $arr_b)
{
	foreach($arr_b as $key => $val) {
		if(is_array($arr_a[$key])) {
			if(!is_array($val)) throw new LightningException("Error in config_merge(): variable type does not match");
			$arr_a[$key] = config_merge($arr[$key], $val);
		} else {
			if(is_array($val)) throw new LightningException("Error in config_merge(): variable type does not match");
			$arr_a[$key] = $val;
		}
	}
	return $arr_a;
}

function custom_match($val_a, $val_b)
{
    $len = strlen($val_a);
    $res = $len ^ strlen($val_b);
    if(!$res) return False;

    for($i = 0; $i < $len; $i++) {
    	$res |= ord($val_a[$i]) ^ ord($val_b[$i]);
    }

    return $res === 0;
}


function show_error($message)
{
	$tbody = '';
	$thead = "<tr>
				<th class='class'>Class</th>
				<th class='file'>File</th>
				<th class='line'>Line</th>
				<th class='function'>Function</th>
			</tr>";
	foreach(array_reverse(debug_backtrace()) as $error) {
		$line  = empty($error['line']) 		? '' : $error['line'];
		$func  = empty($error['function']) 	? '' : $error['function'];
		$file  = empty($error['file']) 		? '' : $error['file'];
		$class = empty($error['class'])		? '' : $error['class'];
		$tbody .= "<tr>
					<td>{$class}</td>
					<td>{$file}</td>
					<td>{$line}</td>
					<td>{$func}</td>
				</tr>";
	}
	$table = "<table>
				<thead>{$thead}</thead>
				<tbody>{$tbody}</tbody>
			</table>";
	exit(include(framework_path . '\Error\500.phtml'));
}

function show_404($message)
{
	exit(include(framework_path . '\Error\404.phtml'));
}


function print_pre($mixed)
{
	$str = print_r($mixed, True);
	echo "<pre>{$str}</pre>";
}

function vd_pre($mixed)
{
	echo "<pre>";
	var_dump($mixed);
	echo "</pre>";
}

