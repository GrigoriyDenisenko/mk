<?php

namespace Framework\Response;

/**
 * Class ResponseRedirect
 * Redirect to another URL via a Location header.
 *
 * @package Framework\Response
 */
class ResponseRedirect extends Response
{
    /**
     * ResponseRedirect constructor.
     * Set the Location header.
     *
     * @param string $route путь для перенаправления
     * @param string $message сообщение, с которым будет выполнено перенаправление (добавляется в GET параметры с ключом redirectmessage)
     */
    public function __construct($url, $message = "")
    {
/*        echo "<hr>redirect URL: " .$url;
        if ($message !== "") {
            Service::get("session")->flush=$message;
        }*/
        // можно через абстрактный класс ResponseType::MOVED_PERMANENTLY (="301")
        parent::__construct('', 302);
        parent::setHeader('Location', $url);
    }
}
