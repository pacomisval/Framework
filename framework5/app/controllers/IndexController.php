<?php
namespace app\controllers;

use core\MVC\Controller as Controller;
use app\models\JugadorModel;

class IndexController extends Controller {

    public function IndexAction() {
        $this->renderView('portada');
        
    }

    public function HistoriaAction() {
        $this->renderView('historia');
    }

    public function JugadoresAction() {
        $jugadores = JugadorModel::where('Nombre_equipo', '=', 'Lakers')->get();
        $this->renderView('jugadores', ['jugadores' => $jugadores]);
    }

    public function RegistroAction() {
        $this->renderView('registro');
    }

    public function LoginAction() {
        $this->renderView('login');
    }
}