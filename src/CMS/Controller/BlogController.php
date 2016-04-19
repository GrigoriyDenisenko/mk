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
    public function editAction($id){
        //$route=Service::get('route');
        //$post = Post::find((int)$id);
        $security=Service::get('security');
        if ($security->isAuthenticated()) {
            $user = $security->getUser();
            //echo '<br>begin edit post User:';
            //var_dump($user);
            //throw new SecurityException('The post edit successfully', Service::get('router')->buildRoute('home'));

            if ($user->role == 'ROLE_ADMIN') {
                try {
                    $post=Post::find($id);
                    //var_dump($post);
                    if (is_null($post)){
                       throw new DatabaseException("Database reading error. Not found record");
                    }
                    if ($this->getRequest()->isPost()) {
                        // берём данные из $_POST чтобы занести в базу
                        $date = new \DateTime();
                        $post->title = $this->getRequest()->post('title');
                        $post->content = trim($this->getRequest()->post('content'));
                        $post->date = $date->format('Y-m-d H:i:s');
                        $validator = new Validator($post);
                        if ($validator->isValid()) {
                            $post->update('id', $id);
                            return $this->redirect($this->generateRoute('home'), 'The data has been update successfully');
                        } else {
                            $error = $validator->getErrors();
                        }
                    }
                } catch (DatabaseException $e) {
                    $error = $e->getMessage();
                    //echo $error;
                }
            } else {
                throw new SecurityException('You are not allowed posts updating', $this->getRequest()->getReferrer());
            }
        }else{
            throw new SecurityException('Please, login', Service::get('router')->buildRoute('login'));
        }
        //$renderer = Service::get('renderer');
        //return new Response($renderer->render(__DIR__ . '/../../Blog/views/Post/add.html.php', array('action' => $this->generateRoute('edit'), 'post' => isset($post)?$post:null, 'edit'=>'edit mode', 'errors' => isset($error)?$error:null)));
        //return $this->render('update.html',
            //array('action' => $this->generateRoute('edit'), 'edit'=>'edit mode', 'errors' => isset($error)?$error:null));
        return $this->render('update.html',
            array('action' => $this->generateRoute('edit_post'), 'post' => isset($post)?$post:null, 'edit'=>'edit mode', 'errors' => isset($error)?$error:null));
    }

    public function deleteAction($id){
        $security=Service::get('security');
        if ($security->isAuthenticated()) {
            //echo '<HR>check role for DELETED post with id: '.$id;
            //$route=Service::get('route');
            $user = $security->getUser();
            //echo '<br>begin delete post User:';
            //var_dump($user);
            //throw new SecurityException('The post delete successfully', Service::get('router')->buildRoute('home'));
            if ($user->role == 'ROLE_ADMIN') {
                $post = new Post();
                $post->delete('id', $id);
                return $this->redirect($this->generateRoute('home'), 'The post delete successfully');
            } else {
                throw new SecurityException('You are not allowed posts updating', $this->getRequest()->getReferrer());
            }
        }else{
            throw new SecurityException('Please, login', Service::get('router')->buildRoute('login'));
        }
    }

}