<?php

namespace core\database;

use \core\database\PdoConnection AS PdoConnection;

class DB {

    private static $instance;
    private $table;
    private $fields = [];
    private $wheres = [];
    private $operators = [
        '=',
        '<',
        '>',
        '<=',
        '>='
    ];

    /**
     * Método para devolver una instancia de DB con la tabla que toca
     *
     * @param [type] $table
     * @return void
     */
    public static function table($table) {
        $instance = new static;
        $instance->setTable($table);
        return $instance;
    }

    private function setTable($table){
        $this->table = $table;
    }

    private function getTable(){
        return $this->table;
    }

    /**
     * selecciona los campos de la tabla para el select
     * ...$fields para que el número de los argumentos pueda ser variable (0,1,2...)
     *
     * @param string ...$fields
     * @return void
     */
    public function select(...$fields){
        foreach ($fields as $field) {
            $this->setField($field);
        }
        return $this;
    }

    private function setField($field) {
        array_push($this->fields, $this->sanitize($field));
    }

    private function sanitize($value) {
        return preg_replace('/[^0-9a-zA-Z_-]/', '', $value);
    }

    public function where($field, $operator, $value) {
        $condition = [
            "field" => $this->sanitize($field),
            "operator" => $this->sanitizeOperator($operator),
            "value" => $this->sanitize($value)
        ];
        $this->setWhere($condition);
        return $this;
    }

    private function sanitizeOperator($operator) {
        if (in_array($operator, $this->operators))
            return $operator;
            else return '=';
    }

    private function setWhere($condition) {
        array_push($this->wheres, $condition);
    }

    public function get() {
        $params = null;
        $sql = 'SELECT ';
        if (empty($this->fields)) {
            $sql.='*';
        } else {
            foreach ($this->fields as $key => $value) {
                $sql.="$value,";
            }
            $sql = substr($sql, 0, -1);
        }
        $sql .= ' FROM '.$this->getTable();
        if (!empty($this->wheres)) {
            $params = array();
            $sql.=' WHERE ';
            foreach ($this->wheres as $condition) {
                $sql.=$condition['field'].$condition['operator'].":".$condition['field'].' AND ';
                $params[":".$condition['field']] = $condition['value'];
            }
            $sql = substr($sql, 0, -5);
        }
        $connection = PdoConnection::getInstance();
        return $connection->select($sql, $params);
    }

    public function insert($record) {
        $sql ='INSERT INTO '.$this->getTable().'(';
        $values = '';
        foreach ($record as $key => $value) {
            $sql.=$key.',';
            $values.=":$key,";
            $params[":".$key] = $value;
        }
        $sql = substr($sql, 0, -1).')';
        $sql.=' VALUES ('.substr($values, 0, -1).')';
        $connection = PdoConnection::getInstance();
        return $connection->insert($sql, $params);
    }

    public function lastInsertId() {
        $connection = PdoConnection::getInstance();
        return $connection->lastInsertId();
    }

    public function delete() {
        $sql = 'DELETE FROM '.$this->getTable();
        $params = array();
        $sql.=' WHERE ';
        foreach ($this->wheres as $condition) {
            $sql.=$condition['field'].$condition['operator'].":".$condition['field'].' AND ';
            $params[":".$condition['field']] = $condition['value'];
        }
        $sql = substr($sql, 0, -5);
        $connection = PdoConnection::getInstance();
        return $connection->delete($sql, $params);
    }

    public function update($record) {
        $sql ='UPDATE '.$this->getTable().' SET ';
        $values = '';
        foreach ($record as $key => $value) {
            $sql.="$key=:$key,";
            //$values.=":$key,";
            $params[":".$key] = $value;
        }
        $sql = substr($sql, 0, -1);
        $sql.=' WHERE ';
        foreach ($this->wheres as $condition) {
            $param = ':'.$condition['field'];
            //Si existe ya el parámetro, le añadimos un 1 para que no sea el mismo,
            //así nos evitamos situaciones como update codigo=:codigo where codigo=:codigo
            if (array_key_exists($param, $params))
                $param = $param.'1';
            $sql.=$condition['field'].$condition['operator'].$param.' AND ';
            $params[$param] = $condition['value'];
        }
        $sql = substr($sql, 0, -5);
        $connection = PdoConnection::getInstance();
        return $connection->insert($sql, $params);
    }


}

