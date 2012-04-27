<?php
/*!
 * @desc Route traffic of site.
 * @author Shawn Melton <shawn.a.melton@gmail.com>
 * @version $id: $
 */
class Router {
	static function run() {
		$controller = new Controller();
		
		$bits = explode('/', str_replace($_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']));
		$action = 'home';
		if( isset($bits[1]) && ($bits[1] = strtolower(preg_replace('/\W/', '', $bits[1]))) != '' ) {
			$action = $bits[1];
		}
		
		$controller->$action();
		$controller->display();
	}
}