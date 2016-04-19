<?php

namespace CMS\Controller;

use Blog\Model\User;
use Blog\Model\Post;
use Framework\Controller\Controller;
use Framework\DI\Service;
use Framework\Exception\SecurityException;
use Framework\Exception\DatabaseException;


class ProfileController extends Controller
{
    /**
     * Update users profile(password)
     *
     * @return \Framework\Response\Response|\Framework\Response\ResponseRedirect
     * @throws SecurityException
     */
    public function updateAction()
    {
        $security=Service::get('security');
        if ($security->isAuthenticated()) {
            if ($this->getRequest()->isPost()) {
                $user=Service::get('security')->getUser();
                $newpassword1=$this->getRequest()->post('newpassword1');
                if ($user->password == $this->getRequest()->post('password')
                    && $newpassword1 == $this->getRequest()->post('newpassword2')
                ) {
                    try {
                        //var_dump($user);
                        $user->password = md5($newpassword1);
                        $user->save();
                        //if ($user->save()){
                            Service::get('security')->setUser($user);
                        //}
                        //return $this->redirect($this->generateRoute('profile'), 'The password update successfully');
                        return $this->redirect($this->generateRoute('home'), 'The password update successfully');
                    } catch (DatabaseException $e) {
                        $errors = array($e->getMessage());
                    }
                } else {
                    return $this->redirect($this->getRequest()->getUri(), 'Password mismatch', 'error');
                }} else {
                return $this->getAction();
            }
        } else {
            throw new SecurityException('Please, login', Service::get('router')->buildRoute('login'));
        }

        return $this->render('profile.html', array('errors' => $errors));
    }

    /**
     * get profile pages
     *
     * @return \Framework\Response\Response
     */
    public function getAction()
    {
        // формируем массив переменных и объектов для передачи в шаблон для изменения данных пользователя
        $user=Service::get('security')->getUser();
        $username= empty($user->name) ? $user->email : $user->name;
        //$posts = Post::find('user_id',$user->id);
        $posts = Post::find($user->id,'edituser_id');
        // var_dump($posts);
        //echo "<hr>USER: {$username}";
        return $this->render('profile.html', array('title'=>'This is your  personal pages',
            'username'=>$username, 'user'=>$user, 'posts'=>$posts));
    }

    public function changepwdAction()
    {
        // формируем массив переменных и объектов для передачи в шаблон для изменения данных пользователя
        $user=Service::get('security')->getUser();
        $username= empty($user->name) ? $user->email : $user->name;
        //$posts = Post::find('user_id',$user->id);
        $posts = Post::find($user->id,'edituser_id');
        // var_dump($posts);
        //echo "<hr>USER: {$username}";
        return $this->render('passwd.html', array('username'=>$username, 'user'=>$user));
    }
}