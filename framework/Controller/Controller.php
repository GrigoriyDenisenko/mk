<?php

namespace Framework\Controller;

use Framework\Request\Request;
use Framework\Response\ResponseRedirect;
use Framework\Response\Response;
use Framework\Renderer\Renderer;
use Framework\DI\Service;

/**
 * Class Controller
 * Controller prototype
 *
 * @package Framework\Controller
 */
class Controller {

    /**
     * Конструктор контроллера
     * @param $request
     */
/*    public function __construct($request = null)
    {
        $this->request = $request;
        $class = get_called_class();
        echo $class;
        echo '<BR>Controller construct with request: ' . $request .'<BR>class: '.$class;
        // self::$logger = Service::get("logger");
    }*/

    /**
     * Rendering method
     *
     * @param   string  Layout file name
     * @param   mixed   Data
     *
     * @return  Response
     */
    public function render($Layout, $Data = array()){
        echo '<BR>CONTROLLER renderer input layout: '. $Layout;
        echo '<HR>DATA:<BR>';
        echo var_dump($Data);
        $class = get_called_class();
        //new Response("test");
        $ControllerName = str_replace('Controller','',basename(str_replace('\\', DIRECTORY_SEPARATOR, $class)));
        // Renderer-у нужно передать полный путь к шаблону
        // Возьмем его из названия модели ($Layout)
        //$renderer = new Renderer($layout, $content);
        //return new Response(Renderer::render("/../views/".$ControllerName."/".$Layout,$Data ));
        //$fullpath = realpath('...' . $layout);
        //$renderer = new Renderer('...'); // Try to define renderer like a service. e.g.: Service::get('renderer');
        //$content = $renderer->render($fullpath, $data);
        $content = Service::get('renderer')->render($ControllerName."/".$Layout.".php",$Data);
        echo '<HR>**********Content:**********<BR>';
        echo $content;
        return  new Response($content);
    }

    /**
     * Redirect
     *
     * @param $uri
     * @param string $message
     * @return ResponseRedirect
     */

    public function redirect( $uri, $message = '' ) {
        if( empty( $uri )) {
            $uri = '/';
        }

        return new ResponseRedirect( $uri, $message );
    }

    /**
     * Get Request
     *
     * @return Request
     */

    public function getRequest() {
        return new Request();
    }
    /**
     * Generate route for redirect or etc.
     *
     * @param $name
     * @return mixed
     */

    public function generateRoute( $name ) {
        return Service::get(['routes'])[$name]['pattern'];
    }

}