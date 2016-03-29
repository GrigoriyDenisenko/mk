<?php

namespace Framework\Exception;


use Framework\DI\Service;
use Framework\Response\ResponseRedirect;

class SecurityException extends \Exception
{
    public function __construct($msg, $url, $code=301){
        $session=Service::get('session');
        $session->ReturnUrl = Service::get('request')->getUri();
        $session->addFlash('info', $msg);
        $resp = new ResponseRedirect($url, $code);
        $resp->send();
    }
}
