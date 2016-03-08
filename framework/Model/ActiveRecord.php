<?php

namespace Framework\Model;


abstract class ActiveRecord {

    protected static $db = null;

    /**
     * Class constructor
     */
    public function __construct(){

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

        // @TODO: build SQL expression, execute
    }
}