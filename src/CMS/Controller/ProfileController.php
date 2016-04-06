<?php

namespace CMS\Controller;

use Blog\Model\User;
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
                $user = Service::get('session')->get('user');if ($user->password == $this->getRequest()->post('password')
                    && $this->getRequest()->post('newpassword1') == $this->getRequest()->post('newpassword2')
                ) {
                    try {
                        $us = new User();
                        $us->email = $user->email;
                        $us->password = md5($this->getRequest()->post('newpassword1'));
                        $us->role = $user->role;

                        $us->update('email', $user->email);
                        return $this->redirect($this->generateRoute('profile'), 'The password update successfully');
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

        $user=Service::get('security')->getUser()->email;

        return $this->render('profile.html', array('content'=>'This is your  personal pages', 'username'=>$user));
    }
}