<?php

namespace Framework\Exception;

use Exception;

/**
 * Class NotAuthException
 * @package framework\Exception
 */
class NotAuthException extends \Exception
{
    /**
     * NotAuthException constructor.
     */
    public function __construct()
    {
        $code = 401;
        $message = 'You are not authorized';
    }
}
