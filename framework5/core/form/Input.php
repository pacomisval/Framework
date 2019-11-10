<?php

namespace core\form;

/**
 * Clase para validar los campos de un formulario
 */
class Input {
    
    /**
     * Archivos de imagen permitidos
     */
    static $whiteList = array ('jpg', 'png', 'bmp');


    /**
     * Comprueba si se han pasado los campos correctos del formulario
     *
     * @param array $fields
     * @param boolean $on
     * @return boolean
     */
    static function check($fields, $on = false) {
        if ($on === false) {
            $on = $_REQUEST;
        }
        foreach ($fields as $value) {	
            if (empty($on[$value])) {
                return false;
            }
            else {
                return true;
            }
        }
       
        /* if($_POST["user"] != null && $_POST["password"] != null) {
            return true;
        } 
        else {
            return false;
        }  */       
    }



    /**
     * Devuelve el valor de un string sanitizado
     *
     * @param string $value
     * @return string
     */
    static function str($value) {
        if($value) {
            $result = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            return $result;
        }
        else {
            echo $value. " es NULL";
        }
    }

    /**
     * Comprueba si la extensión de la imagen es valida
     *
     * @param [type] $path
     * @return boolean
     */
    static function checkImage($path) {
        if($path) {
            $e = pathinfo($path, PATHINFO_EXTENSION);
            foreach($whiteList as $extens) {
                if($e == $extens) {
                    return true;
                }
                else {
                    return false;
                }
            }
        }
        else {
            echo $path. " es NULL";
        }
        
    }

}
