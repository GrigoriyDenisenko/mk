<?php

namespace Framework\Model;


abstract class ActiveRecord {

    protected static $db = null;

    /**
     * Class constructor
     */
    public function __construct(){
        echo 'ActiveRecord construct' ;
    }

    public function getRules(){
        echo 'getRules' ;
        return [];
    }

    public static function getDBCon(){

        if(empty(self::$db)){
            self::$db = Service::get('db');
        }

        return self::$db;
    }

    public abstract function getTable();

    /**
     * Получить 1 запись, если задан идентификатор ($mode) записи
     * а иначе все записи - $mode = 'all'
     */
    public static function find($mode = 'all'){
        echo 'ActiveRecord find with mode'. $mode ;

        $table = static::getTable();

        $sql = "SELECT * FROM " . $table;

        if(is_numeric($mode)){
            $sql .= " WHERE id=".(int)$mode;
        }
        else{

        }

        // PDO request...

        return $result;
    }

    protected function getFields(){

        return get_object_vars($this);
    }

    public function save(){
        $fields = $this->getFields();

        $all_rules = $this->getRules();

        foreach($all_rules as $name => $rules){
            if(array_key_exists($name, $fields)){
                foreach($rules as $rule){
                    $valid = $rule->isValid($fields[$name]);
                }
            }
        }

        // @TODO: build SQL expression, execute
    }
}