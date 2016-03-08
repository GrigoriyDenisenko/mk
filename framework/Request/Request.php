<?php

namespace Framework\Request;

/**
 * Class Request
 * @package Framework\Request
 */
class Request
{
    /**
     * Check request method
     *
     * @return bool
     */
    public function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns parameter from $_POST
     *
     * @param string $param
     * @return string
     */
    public function post($param)
    {
        //@TODO Add filtration
        return $_POST[$param];
    }

    /**
     * Returns parameter from $_GET
     *
     * @param string $param
     * @return string
     */
    public function get($param)
    {
        return $_GET[$param];
    }
}
