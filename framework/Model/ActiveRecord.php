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
     * @param mixed $mode
     * @param mixed|null $value значение, кот. нужно выбрать из поля заданного в параметре
     * Получить 1 запись, если задан идентификатор ($mode) записи
     * а иначе все записи - $mode = 'all'
     */
    public static function find($mode = 'all', $value = null){

        $table = static::getTable();

        $db = Service::get('db');
        $querystring = "SELECT * FROM " . $table;

//        if(is_numeric($mode)){
//            $query .= " WHERE id=".(int)$mode;
//        }
//        else{
//
//        }
        if ($mode === 'all') { // Select all records from the database table
            $querystring .= " ORDER BY date";
            $query = $db->query($querystring);
            $check_query_result = $query;
        } elseif (is_numeric($mode)) { // Select record from the database table with specified ID
            $querystring .= " WHERE id = :id";
            $query = $db->prepare($querystring);
            $query->bindParam(":id", $mode, \PDO::PARAM_INT, 11);
            $check_query_result = $query->execute();
        } elseif (isset($value)) { // Select record from the database table with specified field=>value
            $get_field_query = "SHOW COLUMNS FROM " . $table . " WHERE FIELD = ?";
            $query = $db->prepare($get_field_query);
            $query->execute(array($mode));
            $check = $query->fetchColumn();

            if ($check === false) {
                throw new DatabaseException("Database reading error. Table '{$table}' does not have the field '{$mode}'");
            }

            $querystring .= " WHERE {$mode} = :value";
            $query = $db->prepare($querystring);
            $query->bindParam(":value", $value);
            $check_query_result = $query->execute();
        } else {
            return null;
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
        // запр
        $result=$query->fetchAll(\PDO::FETCH_CLASS, get_called_class());
        // get_called_class - должен сам заполнить поля класса, созданного на базе нашего ActiveRecord
        //
        //echo '<BR>RESULT from table "'.$table.'":<BR>';
        //var_dump($result);

        if ($mode === 'all') {
            // возвращаем весь список
            return $result;
        }

        if (is_numeric($mode) && isset($result[0])) {
            // возвращаем одну строку
            return $result[0];
        }

        return !empty($result) ? $result[0] : null;;
    }

    /**
     * Looks for a match of the email in the database.
     *
     * @param $email
     * @return mixed
     */
    static public function findByEmail($email)
    {
        //echo '<hr>findByEmail: ';
        //var_dump($email);
        return static::find('email', (string)$email);
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


    protected function getFields(){

        return get_object_vars($this);
    }

    public function save(){
        $fields = $this->getFields();

/*        $all_rules = $this->getRules();

        foreach($all_rules as $name => $rules){
            if(array_key_exists($name, $fields)){
                foreach($rules as $rule){
                    $valid = $rule->isValid($fields[$name]);
                }
            }
        }*/

        $table = static::getTable();
        if($table == 'users')
        {
            //echo "<hr>save to user_email: ".$fields['email'];
            //var_dump($fields);

            if (!empty(static::findByEmail($fields['email']))){
                throw new DatabaseException('This E-mail already exist');
            }
        }
        $db = Service::get('db');

        $sth = $db->prepare('SHOW COLUMNS FROM '.$table);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        $sth->execute();
        $colums = array();
        while($row = $sth->fetch()) {
            $colums[] = $row['Field'];
        }
        $query = "INSERT INTO ".$table." SET ";
        foreach($fields as $key => $value){
            if(array_search($key, $colums)){
                $query_parts[] = sprintf("`%s`='%s'", $key, $fields[$key]);
            }
        }
        $query_part = implode(', ', $query_parts);
        $query .= $query_part;

        //$db->beginTransaction();
        $sth = $db->prepare($query);
        $res = $sth->execute();
        //$db->commit();
        if (!$res) throw new DatabaseException('Data save failed');
    }

    /**
     * update table
     * @param $field
     * @param $fieldValue
     */
    public function update($field, $fieldValue)
    {
        $fields = $this->getFields();
        $query = '';
        $values=array();
        foreach ($fields as $col => $val) {
            $query .= $col . '=:' . $col . ', ';
            $values[':'.$col]=$val;
        }
        $query = trim($query);
        $query = substr($query, 0, -1);
        $db = Service::get('db');
        $query = 'UPDATE `' . static::getTable() . '` SET ' . $query . ' WHERE ' . $field . '=:fieldValue';
        $stmt = $db->prepare($query);
        $values[':fieldValue']= $fieldValue;
        $res=$stmt->execute($values);
        if($res==false){
            throw new DatabaseException('Update is failed');
        }
    }

    /**
     * @param $field
     * @param $fieldVal
     */
    public function delete($field, $fieldVal){
        $db=Service::get('db');
        $query = 'DELETE FROM `' . static::getTable() . '` WHERE '.$field.'='.$fieldVal;
        $res=$db->query($query);
        if($res==false){
            throw new DatabaseException('Delete is failed');
        }

    }
}