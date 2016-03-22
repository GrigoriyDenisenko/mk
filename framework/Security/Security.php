<?php
/**
 * Create class Security
 */

namespace Framework\Security;


use Framework\DI\Service;

class Security{


    public function setUser($user){
        echo "<hr>user: ".$user;

        Service::get('session')->set('user', $user);
    }

    public function isAuthenticated(){
        echo "<HR>Session:";
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