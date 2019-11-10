<?php
namespace core\auth;
use app\models\UserModel;

/**
 * Clase para validar usuarios
 */
class Auth {

    /**
     * Devuelve la contraseña encriptada
     *
     * @param string $password
     * @return string
     */
    static function crypt($password) {
        return $passHash = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verifica que el usuario y la contraseña sea correcta
     *
     * @param [type] $password
     * @param [type] $idUser
     * @return boolean
     */
    static function passwordVerify($password, $userName /*$idUser*/) {
        
        $user = new UserModel();
        $res = $user->where(UserModel::getUserNameField(), " = ", $_POST['user'])->get()[0];

        echo "Valor de res";
        echo "<pre>";
        var_dump($res);
        echo "</pre>";
        
        $hash =  $res->password;

        return password_verify($password, $hash);  
    }

    /**
     * Comprueba si el usuario está logeado
     *
     * @return boolean
     */
    static function check() {
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
            return true;
        }
        else {
            return false;
        }
    }

}
