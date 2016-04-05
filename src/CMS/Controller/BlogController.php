<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.10.15
 * Time: 11:57
 */

namespace CMS\Controller;


use Framework\Controller\Controller;
use Framework\DI\Service;
use Framework\Exception\DatabaseException;
use Framework\Exception\SecurityException;
use Framework\Response\Response;
use Blog\Model\Post;
use Framework\Validation\Validator;


class BlogController extends Controller
{
    public function deleteAction($id){
        $security=Service::get('security');
        if ($security->isAuthenticated()) {
            //echo '<HR>check role for DELETED post with id: '.$id;
            //$route=Service::get('route');
            $user = $security->getUser();
            //echo '<br>begin delete post User:';
            //var_dump($user);
// TEST:
            throw new SecurityException('The post delete successfully', Service::get('router')->buildRoute('home'));

/*            if ($user->role == 'ROLE_ADMIN') {
                if ($this->getRequest()->isPost()) {

                    $post = new Post();
                    // $post->delete('id', $id);
                    echo '<HR>DELETED id:'.$id;
                    return $this->redirect($this->generateRoute('home'), 'The post delete successfully');
                }

            } else {
                throw new SecurityException('You are not allowed posts updating', $this->getRequest()->getReferrer());
            }*/
        }else{
            throw new SecurityException('Please, login', Service::get('router')->buildRoute('login'));
        }
        //return $this->redirect('/');
        //throw new SecurityException('Please, login', Service::get('route')->buildRoute('login'));
    }

}