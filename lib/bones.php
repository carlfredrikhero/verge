<?php

ini_set('display_errors', 'On');
error_reporting(E_ERROR | E_PARSE);

define('ROOT', __DIR__ . '/..');

function get($route, $callback){
	Bones::register($route, $callback, 'GET');
}

function post($route, $callback){
	Bones::register($route, $callback, 'POST');
}

function put($route, $callback){
	Bones::register($route, $callback, 'PUT');
}

function delete($route, $callback){
	Bones::register($route, $callback, 'DELETE');
}

class Bones{
	private static $instance;
	public static $route_found = false;
	public $route = '';
	public $method = '';
	public $content = '';
	public $vars = array();
	public $route_segments = array();
	public $route_variables = array();


	public static function get_instance(){
		if (!isset(self::$instance)){
			self::$instance = new Bones();
		}
		
		return self::$instance;
	}
	
	public function __construct() {
		$this->route = $this->get_route();
		$this->route_segments = explode('/', trim($this->route, '/'));
		$this->method = $this->get_method();
	}
	
	protected function get_route(){
		parse_str($_SERVER['QUERY_STRING'], $route);
		if ( $route){
			return '/' . $route['request'];
		} else {
			return '/';
		}
	}
	
	protected function get_method(){
		return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
	}


	public function set($index, $value){
		$this->vars[$index] = $value;
	}
	
	public function render($view, $layout = 'layout'){
		$this->content = ROOT . '/views/' . $view . '.php';
		
		foreach ($this->vars AS $key => $value){
			$$key = $value;
		}
		
		if (!$layout){
			include $this->content;
		} else {
			include ROOT . '/views/' . $layout . '.php';
		}
	}
	
	public static function register($route, $callback, $method){
		if (!static::$route_found){
			$bones = static::get_instance();
			$url_parts = explode('/', trim($route, '/'));
			$matched = null;
			
			if (count($bones->route_segments) == count($url_parts)){
				foreach($url_parts as $key=>$part){
					if (strpos($part, ':') !== false){
						// contains a route variable
						$bones->route_variables[substr($part, 1)] = $bones->route_segments[$key];
					} else {
						// does not contain route variables
						if ($part == $bones->route_segments[$key]){
							if (!$matched){
								// Routes match
								$matched = true;
							}
						} else {
							// Routes don't match
							$matched = false;
						}
					}
				}
			} else {
				// routes are different lengths
				$matched = false;
			}
			
			if (!$matched || $bones->method != $method){
				return false;
			} else {
				static::$route_found = true;
				echo $callback($bones);
			}
		}
	}
	
	public function request($key){
		return $this->route_variables[$key];
	}


	public function form($key){
		return $_POST[$key];
	}
	
	public function make_route($path = ''){
		$url = explode('/', $_SERVER['PHP_SELF']);
		
		if ($url[1] == 'index.php'){
			return $path;
		} else {
			return '/' . $url[1] . $path;
		}
	}
}