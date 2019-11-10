<?php
    use core\MVC\Kernel;
    use core\auth\Auth;
    session_start();

    //Guardamos la ruta principal del framework y el separador de directorios
    $basedir = dirname(dirname(__FILE__));
    DEFINE('ds', DIRECTORY_SEPARATOR);

    //Incluimos nuestro fichero de configuración
    $config = require_once $basedir . ds . "config.php";
    //Incluimos el autoloader para incluir las clases automáticamente
    require_once($basedir . ds . "core" . ds . "AutoLoad.php");

   
    //Crear un nuevo objeto de tipo Kernel
    $kernel = new Kernel();
   //Llamar al método run del objeto creado anteriormente
    $kernel->run();
    