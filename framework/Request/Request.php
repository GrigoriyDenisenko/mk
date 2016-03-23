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
     * @param $varname string ключ
     * @param $filter_name string формат фильтра для filter_var функции
     * @return mixed значение переменной
     */
    public function post($varname = '', $filter_name = 'STRING')
    {
        echo "<hr>search string: ". $varname;
        echo "<br>in post array:";
        var_dump($_POST);
        if ($varname == 'password') {
            return array_key_exists($varname, $_POST) ? md5($this->filter($_POST[$varname], $filter_name)) : null;
        }
        return array_key_exists($varname, $_POST) ? $this->filter($_POST[$varname], $filter_name) : null;
    }

    /**
     * Filter obtained value
     *
     * @param mixed $source
     * @param string $filter_name
     * @return mixed|null
     */
    public function filter($source, $filter_name = 'STRING')
    {
        $result = null;

        switch ($filter_name) {
            case 'STRING':
                $result = filter_var((string)$source, FILTER_SANITIZE_STRING);
                break;
            case 'EMAIL':
                $result = filter_var((string)$source, FILTER_SANITIZE_EMAIL);
                break;
            case 'INT': // Only use the first integer value
                preg_match('~^\d+~', (string)$source, $matches);
                $result = (int)$matches[0];
                break;
        }

        return $result;

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
