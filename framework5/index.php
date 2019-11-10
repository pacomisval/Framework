<?php
    //Simulamos la funcionalidad "mod_rewrite" de Apache para redirigir todo a htdocs/index.php.
    //en /public/.htaccess deberemos asegurarnos de que está la regla una vez esté en producción
    require_once __DIR__.'/public/index.php' ;