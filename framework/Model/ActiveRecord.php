<?php

namespace Framework\Model;

use Framework\DI\Service;
use Framework\Exception\DatabaseException;


abstract class ActiveRecord {

    protected static $db = null;

/*    public function getRules(){
        echo 'getRules' ;
        return [];
    }

    public static function getDBCon(){

        if(empty(self::$db)){
            self::$db = Service::get('db');
        }

        return self::$db;
    }
*/
    public abstract static function getTable();
    //public abstract function getTable();

    /**
     *  @param $mode
     * Получить 1 запись, если задан идентификатор ($mode) записи
     * а иначе все записи - $mode = 'all'
     */
    public static function find($mode = 'all'){

        $table = static::getTable();

        echo '<BR> ! ActiveRecord->find with mode= '. $mode . '<BR> TABLE: ' . $table;
        $db = Service::get('db');


        //echo '!!!!!ActiveRecord find with mode '. $mode . ' TABLE: ' . $table;
/*        $sql = "SELECT * FROM " . $table;

        if(is_numeric($mode)){
            $sql .= " WHERE id=".(int)$mode;
        }
        else{

        }

        // PDO request...

        return $result;*/
    }

    /*protected function getFields(){

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
    }*/
}