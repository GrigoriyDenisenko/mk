<?php
/**
 * Created by PhpStorm.
 * User: grid
 * Date: 05.03.16
 * Time: 18:13
 */
namespace Framework\Exception;

/**
 * Class serves for catching bad responses
 *
 * Class BadResponseTypeException
 * @package Framework\Exception
 */


class BadResponseTypeException extends \Exception
{
    /**
     * BadResponseTypeException constructor.
     * @param string $message
     */
    public function __construct($message = 'Bad Response Type')
    {
        $this->code = 400;
        $this->message = $message;
    }

}