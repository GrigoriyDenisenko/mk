<?php

namespace Framework\Request;

/**
 * Class Request
 * @package Framework\Request
 */
class Request
{
    /**
     * Проверить, является ли запрос, POST запросом
     *
     * @return bool
     */
    public function isPost()
    {
        return $_SERVER["REQUEST_METHOD"] == "POST";
    }

    /**
     * Проверить, является ли запрос, GET запросом
     *
     * @return bool
     */
    public function isGet()
    {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }

    /**
     * Возвращает значение переменной POST запроса по ключу
     * @param $name string ключ
     * @return mixed значение переменной
     */
    public function post($name)
    {
        echo "<hr>search string: ". $name;
        echo "<br>in post array:";
        var_dump($_POST);
        return (array_key_exists($name, $_POST)) ? htmlspecialchars($_POST[$name]) : null;
    }

    /**
     * Возвращает значение переменной GET запроса по ключу
     * @param $name string ключ
     * @return mixed значение переменной
     */
    public function get($name)
    {
        return (array_key_exists($name, $_GET)) ? htmlspecialchars($_GET[$name]) : null;
    }

}
