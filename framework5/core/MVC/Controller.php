<?php
namespace core\MVC;

/**
 * Clase base para los controladores
 */
abstract class Controller
{
    /**
     * Nombre del controlador
     *
     * @var string
     */
    private $controllerName = null;

    /**
     * Carga la acción del controlador
     *
     * @param string $action
     * @param string $controller
     * @param array $params
     * @return void
     */
    public function run($action, $controller, $params = null) {
        $this->controllerName = $controller;
        $this->$action($params);
    }

    /**
     * Crea una nueva vista y ejecuta su método reder
     *
     * @param string $viewName
     * @param array $data
     * @return void
     */
    protected function renderView($viewName, $data = null) {
        $view = new View($viewName);
        $view->render($data);
    }

    /**
     * Retorna el valor de un parámetro de ruta
     *
     * @param string $key
     * @return void
     */
    /*protected function getParam($key) {
        return $this->controllerName->getParam($key);
    }*/

}
