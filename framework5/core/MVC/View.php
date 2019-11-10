<?php
namespace core\MVC;

/**
 * Clase base para las vistas
 */
class View {
    private $view_name;
    private $params;
    public $data;

    /**
     * Constructor de la clase
     *
     * @param string $name
     */
    public function __construct($name) {
            $this->view_name = strtolower($name);
    }

    /**
     * Busca la vista en la carpeta views y la incluye
     *
     * @param array $data
     * @return void
     */
    public function render($data = null) {
        if ($data!=null) {
            foreach ($data as $key => $value) {
                //convertir $key en el nombre de una variable.
                //Se añade un dolar al principio para que lo tome como una variable
                $$key = $value;
            }    
        }
        //Hago accesible $config para las vistas
        $config = $GLOBALS['config'];
        $view = $GLOBALS['basedir'].ds.'app'.ds.'views'.ds.$this->view_name.".php";
        if(!file_exists($view)) {
			throw new KernelException("View file (" . $view . ") not found.");
        }    
        require_once $view;
    }

}