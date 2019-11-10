<?php
namespace app\controllers;

use core\MVC\Controller as Controller;
use app\models\JugadorModel;
use core\database\DB as DB;

class ErrorController extends Controller {

    public function ErrorAction() {
        $this->renderView('error');
        
    }

}