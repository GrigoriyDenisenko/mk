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
     * @param string $url The URL
     * @param int $code The redirect status code
     */
    public function __construct($url, $code = 302)
    {
        echo "<hr>redirect URL: ".$url;
        parent::__construct('', $code);
        $this->setHeader('Location', $url);
    }
}
