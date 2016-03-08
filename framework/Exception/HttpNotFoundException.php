<?php
namespace Framework\Exception;
class HttpNotFoundException extends \Exception{

    /**
     * HttpNotFoundException constructor.
     * @param string $message
     */
    public function __construct($message = 'Page not found')
    {
        $this->code = '404';
        $this->message = $message;
    }
}