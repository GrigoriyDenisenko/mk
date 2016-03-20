<?php
/**
 * Session.php
 *
 */


namespace Framework\Session;


class Session {

    public $messages = [];

    public function __construct(){
        session_start();
    }

    public function __set($name, $val){

    }

    public function __get($name){

    }

    public function addFlash($type, $message){
        $_SESSION['messages'][$type][] = $message;
    }
}