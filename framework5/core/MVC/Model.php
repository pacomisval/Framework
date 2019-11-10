<?php
namespace core\MVC;

//use \core\database\PdoConnection AS PdoConnection;
use \core\database\DB AS DB;
use PDO;

abstract class Model { //implements \ArrayAccess{

    /**
     * Tabla de la bbdd asociada al modelo
     *
     * @var [type]
     */
    protected $table;

    /**
     * Clave primaria de la tabla asociada
     *
     * @var [type]
     */
    protected $key;

    /**
     * Instancia de la clase que se devolverá cuando se llama de forma estática
     *
     * @var [type]
     */
    protected static $instance;

    /**
     * SQL a ejecutar en la bbdd
     *
     * @var [type]
     */
    private $sql;

    /**
     * Registro de la bbdd o nuevo para hacer update o insert en el método save()
     *
     * @var boolean
     */
    protected $exists = false;

    /**
     * Primera creación del objeto para saber si en el método Where()
     * se tiene que crear el sql entero o sólo la condición
     *
     * @var boolean
     */
    protected $new = true;

    /**
     * Nombre y valor de los campos de la tabla
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Valores originales de los campos de la tabla 
     *
     * @var array
     */
    protected $originals = [];

    /**
     * Condiciones where de la consulta
     *
     * @var array
     */
    private $wheres = [];


    /**
     * Devuelve el atributo del array $attributes. Necesario para acceder a las
     * propiedades del objeto de la forma $object->attribute
     *
     * @param [type] $attribute
     * @return void
     */
    public function __get($attribute)
    {
        //echo "Método mágico __get<br>";
        if (isset($this->attributes[$attribute])) {
            return $this->attributes[$attribute];
        }
        return "El atributo $attribute no existe";
    }

    /**
     * Escribe en el atributo del array $attributes. Necesario para acceder a las
     * propiedades del objeto de la forma $object->attribute
     *
     * @param [type] $attribute
     * @return void
     */
    public function __set($attribute, $value){
        /*if (!isset($this->attributes[$attribute])) {
            $this->attributes[$attribute] = $value;
        }*/
        $this->attributes[$attribute] = $value;
    }

    /**
     * Crea una nueva instancia y la devuelve
     *
     * @return object
     */
    private static function getNewInstance(){
        /*if ((!self::$new)||(get_class(self::$instance)!==\get_called_class())) {
            self::$instance = new static;
        }*/
        self::$instance = new static;
        return self::$instance;
    }

    /**
     * Devuelve todos los registros de una tabla
     *
     * @return object[]
     */
    public static function getAll() {
        $instance = self::getNewInstance();
        $results = DB::table($instance->getTable())->get();
        return $instance->toInstances($results);
    }

    /**
     * Convierte un conjunto de resultados en un array de instancias del Modelo
     *
     * @param array $results
     * @return object[]
     */
    private function toInstances($results, $exits=true) {
        $instances = [];
        $i=0;
        foreach ($results as $row) {
            $instance = $this->getNewInstance();
            $j= 0;
            foreach ($row as $key => $value) {
                $instance->attributes[$key] = $value;
                $instance->originals[$key] = $value;
                $j++;
            }
            $instance->exists = $exits;
            $instance->new = false;
            $instances[$i] = $instance;
            $i++;
        }
        return $instances;
    }


    /**
     * Busca un registro en la tabla por su id
     *
     * @param [type] $id
     * @return object
     */
    public static function find($id) {
        $instance = self::getNewInstance();
        $results = DB::table($instance->getTable())
                                ->where($instance->getKey(), '=', $id)
                                ->get();
        return $instance->toInstances($results)[0];
    }

    /**
     * Devuelve el nombre de la tabla sanitizada asociada al modelo
     *
     * @return string
     */
    protected function getTable() {
        $table = preg_replace('/[^0-9a-zA-Z_]/', '', $this->table);
        return $table;
    }

    /**
     * Devuelve el nombre de la clave primaria del modelo
     *
     * @return string
     */
    protected function getKey() {
        return $this->key;    
    }

    private function setWhere($condition) {
        array_push($this->wheres, $condition);
    }

    /**
     * Añade las condiciones del WHERE al array $this->wheres
     *
     * @param string $condition
     * @return object[]
     */
    protected function where($field, $operator, $value) {

        $condition = [
            "field" => $field,
            "operator" => $operator,
            "value" => $value
        ];
        $this->setWhere($condition);
        return $this;
    }

    

    /**
     * Método que se ejecutará cuando se llama a un método no estático de forma estática.
     * El método llamado no puede ser public, si no dará un error
     * ...$arguments se pone para que el número de parámetros pueda ser variable
     *
     * @param [type] $method
     * @param [type] $arguments
     * @return void
     */
    public static function __callStatic($method, $arguments)
    {
        return (new static)->$method(...$arguments);
    }

    /**
     * Método que se ejecutará cuando se llame a un método que no exista en el contexto,
     * por ejemplo llamara al método Where() como público
     *
     * @param [type] $method
     * @param [type] $arguments
     * @return void
     */
    public static function __call($method, $arguments)
    {
        return self::$method(...$arguments);
    }


    /**
     * Añade una condición a la consulta sql mediante el operador OR TODO
     *
     * @param string $condition
     * @return object
     */
    public function orWhere($condition) {
        $this->sql .= " OR " . $condition;
        return $this;
    }

    /**
     * Devuelve un string con la consulta sql
     *
     * @return string
     */
    public function toSql(){
        return $this->sql;
    }

    /**
     * Ejecuta la consulta sql y devuelve el resultado 
     * en forma de array de objetos 
     *
     * @return object[]
     */
    protected function get($params = null) {
        $dbInstance = DB::table($this->getTable());
        foreach ($this->wheres as $condition) {
            $dbInstance->where($condition['field'],$condition['operator'],$condition['value']);
        }
        $results = $dbInstance->get();
        return $this->toInstances($results);
    }


    /**
     * Hace un update o un insert en la bbdd
     *
     * @return void
     */
    public function save(){
        $paramas = array();
        if ($this->exists) {
            //var_dump();
            $attr = array_diff($this->attributes, $this->originals);
            if (!empty($attr)) {
                foreach ($attr as $key => $value) {
                    $params[$key] = $value;    
                }
                return DB::table($this->getTable())
                    ->where($this->getKey(), '=', $this->getKeyValue())
                    ->update($params);
            }
        } else {
            foreach ($this->attributes as $key => $value) {
                $params[$key] = $value;    
            }
            return DB::table($this->getTable())->insert($params);
        }
    }

    /**
     * Devuelve el último Id insertado
     *
     * @return int
     */
    public function lastInsertId() {
        return DB::table($this->getTable())->lastInsertId();
    }

    /**
     * Borrar el registro de la bbdd. Tiene que borrar OBLIGATORIAMENTE por la clave primaria
     *
     * @return boolean
     */
    public function delete() {
        //return DB::table($this->getTable())->where()->delete();
        //$id = 
        return DB::table($this->getTable())
                                ->where($this->getKey(), '=', $this->getKeyValue())
                                ->delete();
    }

    /**
     * Devuelve el valor original de la clave primaria
     *
     * @return string
     */
    private function getKeyValue() {
        return $this->originals[$this->getKey()];
    }

}