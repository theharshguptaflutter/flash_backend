<?php
/*
|--------------------------------------------------------------------------
| Utility Helpers 
|--------------------------------------------------------------------------
|
| Defined helpers for the Application.
|
*/

if ( ! function_exists('pr') )
{
	/**
	 * recursive dump
	 *
	 * @param mixed $var
	 * @param bool $return
	 * @return string
	 */
	function pr($var, $return=false, $html=true){	
		// pattern
		$pattern = $html ? '<pre>%s</pre>' : '%s';
		// output
		$output = sprintf( $pattern, print_r($var, true) );
		// return 
		if($return) return $output;
		// print
		echo $output;
	}
}

