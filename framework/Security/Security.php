<?php
/**
 * Create class Security
 */

namespace Framework\Security;


use Framework\DI\Service;

class Security{


    /**
     * Checks if the user is authorized.
     *
     * @return bool
     */
    public function isAuthenticated(){
        echo "<HR>Checks session if the user is authorized:";
        var_dump($_SESSION);
        return !empty($_SESSION['user']);
    }

    public function clear(){
        Service::get('session')->delFromSess('user');
    }

    public function generateToken(){
        if (Service::get('session')->get('token')){
            return Service::get('session')->get('token');
        }else{
            $token = md5(Service::get('session')->getSessID());
            setcookie('token', $token);
            Service::get('session')->set('token', $token);
        }
    }

    /**
     * Записываем в текущую сессию информацию по авторизированому пользователю
     *
     * @param $user
     *
     */
    public function setUser($user){
        echo "<hr>user: ";
        var_dump($user);
        // $user - передаётся как объект, поэтому для записи в сессию нам нужно его
        // сериализовать или взять те поля, кот. нужно хранить в сессии
        Service::get('session')->user = serialize($user);
        // чере __set: $_SESSION['is_authenticated'] = true
        Service::get('session')->isAuthenticated=true;
        echo "<hr>yes login: ";
        //echo Service::get('session')->isAuthenticated;
    }

    public function getUser( $userSessionName = 'user' ) {
        return isset( $_SESSION[$userSessionName] ) ? $_SESSION[$userSessionName] : null;
    }

    public function checkToken(){
        $token = (Service::get('request')->post('token'))?Service::get('request')->post('token'):null;
        if(!is_null($token)){
            return ($token == $_COOKIE['token'])?true:false;
        }else{
            return true;
        }

    }

}