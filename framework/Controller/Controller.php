<?php

namespace Framework\Controller;

use Framework\Response\Response;

/**
 * Class Controller
 * Controller prototype
 *
 * @package Framework\Controller
 */
abstract class Controller {

    /**
     * Rendering method
     *
     * @param   string  Layout file name
     * @param   mixed   Data
     *
     * @return  Response
     */
    public function render($layout, $data = array()){

        // @TODO: Find a way to build full path to layout file
        $fullpath = realpath('...' . $layout);

        $renderer = new Renderer('...'); // Try to define renderer like a service. e.g.: Service::get('renderer');

        $content = $renderer->render($fullpath, $data);

        return new Response($content);
    }
}