<?php
namespace app\controllers;

use core\MVC\Controller as Controller;
use app\models\JugadorModel;
use app\models\ComentarioModel;


class JugadorController extends Controller {


    public function DatosJugadorAction($params) {
        $idJugador = $params['idJugador'];
        $jugador = JugadorModel::find($idJugador);
        $com = ComentarioModel::where(ComentarioModel::getIdJugadorField(), " = ", $idJugador)->get();
        // echo "ver Jugador<br>";
        // var_dump($jugador);
        // echo "ver Jugador1<br>";
        // var_dump($jugador1);
        $this->renderView('jugador', ['jugador' => $jugador, 'com' => $com]);
    }

}