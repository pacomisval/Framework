<?php
namespace core\MVC;

use app\controllers;

/**
 * Class Kernel: Clase que extiende a Router. Se encarga de cargar el controlador y la 
 * acción que toca una vez leída la ruta
 */
class Kernel extends Router{
	/**
	 * Controlador a cargar
	 *
	 * @var string
	 */
	private $controllerName = "";

	/**
	 * Acción del controlador
	 *
	 * @var string
	 */
	private $actionName = "";

	/**
	 * Acción por defecto
	 *
	 * @var string
	 */
	private $defaultActionName = "error";

	/**
	 * Controlador por defecto
	 *
	 * @var string
	 */
	private $defaultControllerName = "error";

	/**
	 * constructor de la clase. Carga el array routes y llama al método addRoutesFromFiles()
	 * de Router
	 */
    public function __construct() {
		$routesFile = $GLOBALS['basedir'].ds.'routes'.ds.'web.php';
		if(!file_exists($routesFile)) {
			throw new KernelException("Router configuration file (" . $routesFile . ") not found.");
		}
		$routes = require_once $routesFile;
		if(!is_array($routes) || !array_key_exists("routes", $routes)) {
			throw new KernelException("Invalid routes configuration file");
		}
		$this->addRoutesFromFile($routes);        
	}

	/**
	 * Almacena el nombre del controlador (primera letra en mayúsculas y añadiendo la 
	 * palabra Controller) en la variable $controllerName
	 *
	 * @param string $controllerName
	 * @return void
	 */
	private function setControllerName($controllerName) {
		if(!is_string($controllerName)) {
			throw new KernelException("Invalid Controller Name.");
		}

		$this->controllerName = ucfirst(strtolower($controllerName)) . "Controller";
	}

	/**
	 * Almacena el nombre de la acción (primera letra en mayúsculas y añadiendo la 
	 * palabra Controller) en la variable $actionName
	 *
	 * @param [type] $actionName
	 * @return void
	 */
	private function setActionName($actionName) {
		if(!is_string($actionName)) {
			throw new KernelException("Invalid Action Name.");
		}

		$this->actionName = ucfirst(strtolower($actionName)) . "Action";
	}

	/**
	 * Llama al método pareUriRouter() de Router para buscar la ruta que coincida.
	 * Si la encuentra, carga el controlador y llama a su método run()
	 *
	 * @return void
	 */
	public function run() {
		if(($route = $this->parseUriRouter()) != null) {
			$this->setControllerName($route["controller"]);
			$this->setActionName($route["action"]);
		}
		else {
			$this->setControllerName($this->defaultControllerName);
			$this->setActionName($this->defaultActionName);
		}

		$controllerName = 'app\controllers\\' . $this->controllerName; 
		$controller = new $controllerName();
		$controller->run($this->actionName, $this, $this->params);
	}

}

