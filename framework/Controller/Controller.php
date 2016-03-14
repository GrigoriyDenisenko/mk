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
     * Конструктор контроллера
     * @param $request
     */
    public function __construct($request = null)
    {
        echo 'Controller construct with request: ' . $request ;
        $this->request = $request;
        // self::$logger = Service::get("logger");
    }

    /**
     * Rendering method
     *
     * @param   string  Layout file name
     * @param   mixed   Data
     *
     * @return  Response
     */
    public function render($Layout, $Data = array()){
        echo 'RENDER!!!!!!!!!!!!!';
        //$renderer = new Renderer($layout, $content);
        //return new Response($renderer->render());
    }
/*    public function render($layout, $data = array()){
        // @TODO: Find a way to build full path to layout file
        echo 'RENDER!!!!!!!!!!!!!';

        $class = get_called_class();

        echo $class;

        //$fullpath = realpath('...' . $layout);

        //$renderer = new Renderer('...'); // Try to define renderer like a service. e.g.: Service::get('renderer');

        //$content = $renderer->render($fullpath, $data);

        return new Response($content);
    }*/
}