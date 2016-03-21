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

    /**
     *  @param $mode
     * Получить 1 запись, если задан идентификатор ($mode) записи
     * а иначе все записи - $mode = 'all'
     */
    public static function find($mode = 'all'){

        $table = static::getTable();

        $db = Service::get('db');
        $querystring = "SELECT * FROM " . $table;

//        if(is_numeric($mode)){
//            $query .= " WHERE id=".(int)$mode;
//        }
//        else{
//
//        }

        if (is_numeric($mode)) {
            $querystring .= " WHERE id = :id";
            $query = $db->prepare($querystring);
            $query->bindParam(":id", $mode, \PDO::PARAM_INT, 11);
            $check_query_result = $query->execute();
        } else {
            $querystring .= " ORDER BY date";
            $query = $db->query($querystring);
            $check_query_result = $query;
        }
        //echo '<BR>ActiveRecord find with mode '. $mode . ' TABLE: ' . $table.'<BR>querystring= '.$querystring;
        // PDO request...
        // $result = array();
        // $sql = $db->prepare($query);
        // $sql->execute();
        if ($check_query_result === false) {
            $error_code = is_numeric($mode) ? $query->errorCode() : $db->errorCode();
            throw new DatabaseException('Database reading error: ' . $error_code);
        }
        // PDO: Fetch class option to send fields to constructor as array
        $result=$query->fetchAll(\PDO::FETCH_CLASS, get_called_class());
        // get_called_class - должен сам заполнить поля класса, созданного на базе нашего ActiveRecord
        //
        echo '<BR>RESULT from table "'.$table.'":<BR>';
        var_dump($result);
        if (is_numeric($mode) && isset($result[0])) {
            return $result[0];
        }
        return $result;
    }

    /*How about using magic __set() method:
    class MyClass
    {
        protected $record = array();

        function __set($name, $value) {
            $this->record[$name] = $value;
        }
    }
    $results->setFetchMode(PDO::FETCH_CLASS, 'MyClass');

    PHP will call this magic method for every non-existent property passing in its name and value.*/




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