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
        echo "<hr>PUT TOO session: ".$name;
        $_SESSION[$name] = $val;
        var_dump($_SESSION);
    }

    public function __get($name){
        echo "<hr>Get from session: ".$name;
        return array_key_exists($name, $_SESSION) ? $_SESSION[$name] : null;
    }

    public function delFromSess($key){
        unset ($_SESSION[$key]);
    }

    public function addFlash($type, $message){
        echo "SET FLASH";
        $_SESSION['messages'][$type][] = $message;
    }

    public function getFlash()
    {
        echo "GET FLASH";
        // $flash=isset($_SESSION['messages']) ? $_SESSION['messages'] : [];
        if (isset($_SESSION['messages'])) {
            $flash = $_SESSION['messages'];
            // очистим сессионные сообщения
            unset($_SESSION['messages']);
        }else{
            $flash = array();
        }
        return $flash;
    }
}