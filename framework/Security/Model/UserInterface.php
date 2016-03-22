<?php


namespace Framework\Security\Model;


/**
 * Interface UserInterface интерфейс, описывающий пользователя. Необходимо имплментировать функцию getRole()
 * @package Framework\Security\Model
 */
interface UserInterface
{
    /**
     * Возвращает роль пользователя
     * @return mixed роль пользователя
     */
    public function getRole();
}