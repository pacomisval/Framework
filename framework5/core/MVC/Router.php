<?php
namespace core\MVC;

/**
 * Class Router: Esta clase nos servirá para leer nuestras rutas y cargar
 * la que toque en cada momento
 */
class Router {
	/**
	 * Array con los parámetros de las rutas (:idCliente, por ejemplo)
	 *
	 * @var array
	 */
	protected $params = array();


	private $queries = array();

	/**
	 * Array con las rutas del ficheros routes.php
	 *
	 * @var array
	 */
	private $routers = array();

	/**
	 * Añade una ruta al array $routes
	 *
	 * @param array $data
	 * @return void
	 */
	public function addRoute(array $data) {
		array_push($this->routers, $data);
	}

	/**
	 * Devuelve el valor del parámetro $key del array $params
	 *
	 * @param string $key
	 * @return string or null
	 */
	public function getParam($key) {
		if(array_key_exists($key, $this->params)) {
			return $this->params[$key];
		}

		return null;
	}

	/**
	 * Lee el fichero routes.php y llama a addRoute() para ir añadiéndolas
	 *
	 * @param array $routes
	 * @return void
	 */
	protected function addRoutesFromFile(array $routes) {
		foreach ($routes["routes"] as $currentRoute) {
			$this->addRoute($currentRoute);
		}
	}

	/**
	 * Lee la url del navegador y busca si el patrón coincide con alguna ruta
	 * del array $routes
	 *
	 * @return string
	 */
	protected function parseUriRouter() {
		$validRoute = null;
		$uri = trim($_SERVER["REQUEST_URI"], "/");

		//buscar la ruta en el array $routes
		foreach($this->routers as $currentRoute) {
			$route = trim($currentRoute["route"], "/");
			$routerPattern = "#^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', preg_quote($route)) . "$#D";
	
			// Params values that will be assigned to there respective keys
			$matchesParams = array();
			// Check if the URI matches the current route
			if(preg_match_all($routerPattern, $uri, $matchesParams)) {
				// Keys for the params
				$keys = array();
	
				// Remove the first element 
				array_shift($matchesParams);
				
				// Getting the keys names
				preg_match_all('/\\:([a-zA-Z0-9\_\-]+)/', $route, $keys);
				
				// Remove the first element sense is no necesary
				array_shift($keys);
	
				// Assign value to key
				for ($i = 0; $i < count($keys[0]); $i++) {
					$this->params[$keys[0][$i]] = $matchesParams[$i][0];
				}
				
				$validRoute = $currentRoute;
				break; 
			}
		}
		return $validRoute;
	}

}
