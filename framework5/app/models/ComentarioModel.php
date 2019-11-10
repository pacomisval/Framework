<?php
namespace app\models;

use core\MVC\Model as Model;

class ComentarioModel extends Model {
    protected $table = 'comentarios';
    protected $key = 'idComentario';

    protected static $comentarioField = 'comentario';
    protected static $idUsuarioField = 'idUsuario'; 
    protected static $idJugadorField = 'idJugador';
    
    static function getComentarioField() {
        return self::$comentarioField;
    }
    
    static function getIdUsuarioField() {
        return self::$idUsuarioField;
    }

    static function getIdJugadorField() {
        return self::$idJugadorField;
    }

    
}