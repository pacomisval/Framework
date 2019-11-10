<?php
namespace app\models;

use core\MVC\Model as Model;

class UserModel extends Model {
    protected $table = 'usuarios';
    protected $key = 'id'; 
    protected static $userNameField = 'usuario'; 
    protected static $passwordField = 'password';
    
    
    static function getUserNameField() {
        return self::$userNameField;
    }

    static function getPasswordField() {
        return self::$passwordField;
    }

}