<?php
/**
 * Session.php
 *
 */


namespace Framework\Session;


use Blog\Model\Post;

class Session {

    public $messages = [];

    public function __construct(){
        session_start();
    }

    public function __set($name, $val){
        //echo "<hr>PUT TOO session: ".$name;
        $_SESSION[$name] = $val;
        //var_dump($_SESSION);
    }

    public function __get($name){
        //echo "<hr>Get from session: ".$name;
        return array_key_exists($name, $_SESSION) ? $_SESSION[$name] : null;
    }

    public function delFromSess($key){
        unset ($_SESSION[$key]);
    }

    public function addFlash($type, $message){
        //echo "SET FLASH";
        $_SESSION['messages'][$type][] = $message;
    }

    public function getFlash()
    {
        //echo "GET FLASH";
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

    /**
     * Store the post data in the session.
     *
     * @param array $postdata
     */
    public function savePost($postdata)
    {
        //echo "Save post:";
        //var_dump($postdata);
        $_SESSION['post'] = $postdata;
    }

    /**
     * Get the post data that are stored in the session.
     *
     * @return Post|null
     */
    public function getFromPost()
    {
        if (isset($_SESSION['post'])) {
            $post = (object)$_SESSION['post'];
            unset($_SESSION['post']);
            //echo "<hr>from:";
            //var_dump($post);
            return $post;
        }else{
            return null;
        }
    }

    public function getSessID(){
        return session_id();
    }

}