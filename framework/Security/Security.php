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
        //echo "<HR>Checks session if the user is authorized Session:";
        //var_dump($_SESSION);
        //echo "<HR>Post:";
        //var_dump($_POST);
        return !empty($_SESSION['user']);
    }

    public function clear(){
        $session=Service::get('session');
        $session->delFromSess('user');
        $session->delFromSess('token');
    }

    public function generateToken(){
        if (isset($_SESSION['token'])){
            return $_SESSION['token'];
        }else{
            $token = md5(Service::get('session')->getSessID().uniqid());
            //$token = md5("test");
            // записываем в кукис, чтобы токен запомнился при разрыве сессии
            setcookie('token', $token);
            Service::get('session')->token = $token;
        }
    }

    /**
     * Записываем в текущую сессию информацию по авторизированому пользователю
     *
     * @param $user
     *
     */
    public function setUser($user){
        //echo "<hr>user: ";
        //var_dump($user);
        // $user - передаётся как объект, поэтому для записи в сессию нам нужно его
        // сериализовать или взять те поля, кот. нужно хранить в сессии
        Service::get('session')->user = serialize($user);
        // чере __set: $_SESSION['is_authenticated'] = true
        //Service::get('session')->isAuthenticated=true;
        //echo "<hr>yes login: ";
        //echo Service::get('session')->isAuthenticated;
    }

    public function getUser() {
        // разворачиваем из сессии объект $user
        return isset( $_SESSION['user'] ) ? unserialize($_SESSION['user']) : null;
    }

    public function checkToken(){
        if(!isset($_REQUEST['token'])||!isset( $_SESSION['token'])){
            // в post запросе и куках (_POST+_COOKIE)нет поля token
            // или в сессии нет записанного токена - не с чем сверять
            return false;
        }
        $session_token = $_SESSION['token'];
        $post_token=$_REQUEST['token'];
         //if (empty($session_token = Service::get('session')->getSessionToken($form))) { // Check if session is started and token is transmitted, if not return an error
            // в сессии нет записанного токена - нес чем сверять
            //return false;
        //}
        // если хоть один не пустой, проверяем на равенство, а иначе сразу = false
        return (!empty($session_token.$post_token)) && ($session_token == $post_token) ? true : false;

    }

}