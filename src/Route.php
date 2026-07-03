<?php
/**
 * The idea for this object is to provide a simple way routing.
 * @author António Lira Fernandes
 * @version 1.1
 * @updated 04-08-2025 21:50:00
 * https://github.com/alfZone/routes/
 * 
 */

namespace src;

use src\Router;

class Route
{
	protected static $router;

	private function __construct(){
    
  }

	protected static function getRouter()	{
		if(empty(self::$router)) {
			self::$router = new Router;
		}
		return self::$router;
	}

	public static function post($pattern, $callback){
		return self::getRouter()->post($pattern, $callback);
	}

	public static function get($pattern, $callback){
    //echo "aqui";
		return self::getRouter()->get($pattern, $callback);
	}

	public static function put($pattern, $callback){
		return self::getRouter()->put($pattern, $callback);
	}

	public static function delete($pattern, $callback){
		return self::getRouter()->delete($pattern, $callback);
	}

	public static function resolve($pattern){
		return self::getRouter()->resolve($pattern);
	}

	public static function translate($pattern, $params){
		return self::getRouter()->translate($pattern, $params);
	}

}




?>
<?php
/**
 * The idea for this object is to provide a simple way routing.
 * @author António Lira Fernandes
 * @version 1.1
 * @updated 04-08-2025 21:50:00
 * https://github.com/alfZone/routes/
 * 
 */

namespace src;

use src\Request;
use src\Dispacher;
use src\RouteCollection;

class Router 
{
	
	protected $route_collection;
	public $dispacher;

	public function __construct(){
		$this->route_collection = new RouteCollection;
		$this->dispacher = new Dispacher;
	}

	public function get($pattern, $callback)	{	
		$this->route_collection->add('get', $pattern, $callback);
		return $this;
	}

	public function post($pattern, $callback)	{	
		$this->route_collection->add('post', $pattern, $callback);
		return $this;	
	}

	public function put($pattern, $callback)	{	
		$this->route_collection->add('put', $pattern, $callback);
		return $this;	
	}

	public function delete($pattern, $callback)	{
		$this->route_collection->add('delete', $pattern, $callback);
		return $this;	
	}

	public function find($request_type, $pattern)	{
		return $this->route_collection->where($request_type, $pattern);
	}
	
	protected function dispach($route, $params, $namespace = "app\\"){
		return $this->dispacher->dispach($route->callback, $params, $namespace);
	}
	
	public function notFound(){
		return header("HTTP/1.0 404 Not Found", true, 404);
	}


	public function resolve($request){	
		$route = $this->find($request->method(), $request->uri());
		if($route){
			$params = $route->callback['values'] ? $this->getValues($request->uri(), $route->callback['values']) : [];
			return $this->dispach($route, $params);
		}
		return $this->notFound();

	}

	protected function getValues($pattern, $positions){
		$result = [];
		$pattern = array_filter(explode('/', $pattern));
		foreach($pattern as $key => $value){
			if(in_array($key, $positions)) {
				$result[array_search($key, $positions)] = $value;
			}
		}
		return $result;	
	}

	public function translate($name, $params)	{
		$pattern = $this->route_collection->isThereAnyHow($name);
		if($pattern)
		{
			$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
			$server = $_SERVER['SERVER_NAME'] . '/';
			$uri = [];
			foreach(array_filter(explode('/', $_SERVER['REQUEST_URI'])) as $key => $value){
				if($value == 'public') {
					$uri[] = $value;
					break;
				}
				$uri[] = $value;
			}
			$uri = implode('/', array_filter($uri)) . '/';
			return $protocol . $server . $uri . $this->route_collection->convert($pattern, $params);
		}
		return false;
	}
}

?>
