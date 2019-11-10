<?php
namespace app\controllers;

use core\MVC\Controller as Controller;
use app\models\ComentarioModel;
use core\form\Input;
use core\auth\Auth;

class ComentarioController extends Controller {

    public function comentarioAction() {
        $com = Input::str($_POST['text']);
        $idJugador = Input::str($_POST['idJugador']);

        $nomUsuario = $_COOKIE['DWS_framework'];
        $idUsuario = $_COOKIE[$nomUsuario];

        echo "Este es el Id de Usuario<br>";
        echo $idUsuario."<br>";

        if(Auth::check()) {
            if (input::check(['text', 'idJugador'], $_POST)) {
            
                $c = $this->createComent($com, $idUsuario, $idJugador);
                if($c > 0) {
                    header('Location: '.$GLOBALS['config']['site']['root'].$this->redirect_to.'/jugador/'. $idJugador);
                    echo "<h1>Congratulations</h1>";
                }
                else {
                    echo "Algo ha fallado";
                }
            }
            else {
                echo "Comentario o/e IdJugador vacios";
            }
        }
        else {
            echo "Debes logearte para publicar un comentario";
        }
        
    }

    public function createComent($com, $idUsuario, $idJugador) {
             
        $comentario = new ComentarioModel();     
        $comentarioField = $comentario->getComentarioField();
        $idUsuarioField = $comentario->getIdUsuarioField();    
        $idJugadorField = $comentario->getIdJugadorField();
       
        $comentario->$comentarioField = $com;
        $comentario->$idUsuarioField = $idUsuario;
        $comentario->$idJugadorField = $idJugador;

        if($comentario->save()) {
            return $comentario->lastInsertId();
        }
        else {
            header('Location: '.$GLOBALS['config']['site']['root'].$this->redirect_to.'login');
        }
    }



}