<?php
namespace app\models;

use core\MVC\Model as Model;

class JugadorModel extends Model {
    protected $table = 'jugadores';
    protected $key = 'codigo';    
}