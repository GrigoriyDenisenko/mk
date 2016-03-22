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
        $_SESSION[$name] = $val;
    }

    public function __get($name){
        return array_key_exists($name, $_SESSION) ? $_SESSION[$name] : null;
    }

    public function addFlash($type, $message){
        $_SESSION['messages'][$type][] = $message;
    }
}