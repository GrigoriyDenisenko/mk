<?php

namespace Framework\Response;

/**
 * Serves for JSON Responses
 *
 * Class JsonResponse
 * @package Framework\Response
 */
class JsonResponse extends Response {

    public $content;

    /**
     * Fills the content
     *
     * JsonResponse constructor.
     * @param string $array
     */
    function __construct($json_array, $code = 200, $type = 'application/json') {
        if (empty($json_array)) {
            $code = 500;
            $json_array = new \ArrayObject();
        }
        parent::__construct(json_encode($json_array), $code, $type);
    }
}