<?php
namespace app\controllers;

use core\MVC\Controller as Controller;
use app\models\UserModel;
use core\form\Input;
use core\auth\Auth;

/**
 * Clase para el login de usuarios
 */
class LoginController extends Controller {
    /**
     * Página donde será redirigido si el registro es correcto
     *
     * @var string
     */
    private $redirect_to = '/';


    /**
     * Comprueba si los datos son correctos
     *
     * @return void
     */
    public function ValidateAction() {
        
        if (input::check(['user', 'password'], $_POST)) {
            $userName = input::str($_POST['user']);
            $password = input::str($_POST['password']);

            
            $res = UserModel::where(UserModel::getUserNameField(), " = ", $_POST['user'])->get()[0];
            $idUsuario = $res->id;
            echo "valor de RES <br>";
            echo "<pre>";
            var_dump($res);
            echo "</pre><br>";

            echo "valor de idUsuario<br>";
            echo $idUsuario ."<br>";

            if (auth::passwordVerify($password, $userName)) {
                //$jvt = auch:: createToken($usermName)
                //$this->setSession($userName);
                $this->setSession($userName, $idUsuario);
                header('Location: '.$GLOBALS['config']['site']['root'].$this->redirect_to);
            }
        }
        echo "Usuario o password incorrectos";
    }

    /**
     * Destruye la sesión y borra la cookie
     *
     * @return void
     */
    public function LogoutAction() {
        session_unset();
        session_destroy();
        setcookie('DWS_framework', '', -1);
        header('Location: '.$GLOBALS['config']['site']['root']);
    }

    /**
     * Crea la cookie y le pasa el id de la sesión
     *
     * @param [type] $userId
     * @return void
     */
/*     private function setSession($userName) {
        $_SESSION['logged_in'] = true;
        $_SESSION['userName'] = $userName;
        setcookie('DWS_framework', $userName, time() + (60 * 60 * 24 * 5));
    } */

    private function setSession($userName, $idUsuario = null) {
        $_SESSION['logged_in'] = true;
        $_SESSION['userName'] = $userName;
        setcookie('DWS_framework', $userName, time() + (60 * 60 * 24 * 5));
        setcookie($userName, $idUsuario, time() + (60 * 60 * 24));
    }

}