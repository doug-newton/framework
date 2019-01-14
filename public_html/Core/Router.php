<?php

namespace Core;

# handles get and post routes with mapped, anonymous functions
#
# usage:
#
# this would define a get route to foo.
# for a post route, you would use 'P'
# for the empty route, '', (nothing after domain), use ['G', '', function(){}]
#
# if /bar handles both GET and POST requests, then use
# 	['GP', 'bar', function() {
# 		/* do stuff
# 		*/
# 	}]
#
# Router::register([
# 	['G', 'foo', function() {
# 		/* you would require the entry view script here
# 		*/
#	}],
#
#	/*..*/
# ]);

/*	a large portion of this class's functionality is unused
 *
 *	this is because it originally handled routes of the form
 *		/foo/bar/baz/baf
 *		(where bar,baz,baf are parameters to route 'foo')
 *		below, these are referred to as "dynamic routes"
 *
 * 	however, the added reged complexity introduced bugs into an existing
 * 	application
 *
 *	for simplification, all <i>view</i> parameters are passed as simple
 *	GET variables
 */

class Router {
	private static $static_routes = [
		'GET' => [], 'POST' => []
	];

	private static $dynamic_routes = [
		'GET' => [], 'POST' => []
	];

	protected static $redirect_url;

	public static function redirect($url) {
		self::$redirect_url = $url;
	}

	/*	unused, and this would be ajax/session-link */
	public static function followRedirect() {
		if (!empty(self::$redirect_url)) {
			echo '<script>window.location.href="'.self::$redirect_url
				.'"</script>';
			self::$redirect_url = "";
		}
	}

	private static function get($name,$function) {
		if (preg_match('/{.+}/', $name)) {
			self::$dynamic_routes['GET'][$name] = $function;
		} else {
			self::$static_routes['GET'][$name] = $function;
		}
	}

	private static function post($name,$function) {
		if (preg_match('/{.+}/', $name)) {
			self::$dynamic_routes['POST'][$name] = $function;
		} else {
			self::$static_routes['POST'][$name] = $function;
		}
	}

	private static function getAndPost($name,$function) {
		self::get($name, $function);
		self::post($name, $function);
	}
	public static function register($routes) {
		foreach ($routes as $route) {
			assert(sizeof($route)==3);

			# method is G, P, or GP
			$method = $route[0];
			$url = $route[1];
			$function = $route[2];

			$url = parse_url($url, PHP_URL_PATH);

			switch ($method) {

			case 'G':
				self::get($url, $function);
				break;

			case 'P':
				self::post($url, $function);
				break;

			case 'GP':
				self::getAndPost($url, $function);
				break;

			default:
				die('Could not read route entry in Router::register');
				break;

			}
		}
	}

	private static function url_format_to_regex($input) {
		$ret = preg_replace('/{.+}/','.+','/'.str_replace('/','\/',$input).'/'); 
		return $ret;
	}

	private function url_matches($format, $input) {
		if (preg_match(self::url_format_to_regex($format), $input)) {
			return true;
		}
		else return false;
	}

	public static function setDefault($defaultCallback) {
		self::$default_callback = $defaultCallback;
	}

	private static $default_callback;

	public static function useDefault() {
		$function = self::$default_callback;

		//	throw exception if the default was not set, because this is critical

		if (isset($function)) { 
			$function(); 
		} else {
			throw new \Exception("no default callback set!");
		}

		$function();

	}

	/*	all routes are 'static'
	 *	arguments are GET arguments, no fancy \/\//\ regex stuff required
	 */

	# route the url from the apache rewrite engine to a defined route
	public static function handleRequest() {
		$url = $_GET['url'];
		$requestMethod = $_SERVER['REQUEST_METHOD'];

		$path = parse_url($url, PHP_URL_PATH);

		# look up the named route by path

		# first try to read the route as static
		if (isset(self::$static_routes[$requestMethod][$path])) {
			$function = self::$static_routes[$requestMethod][$path];
		} else {
			$function = self::$default_callback;
		}

		# if the static route is found, invoke it

		if (isset($function)) { 
			$function(); 
		} else {
			throw new \Exception("no default callback set!");
		}

	}
}

?>
