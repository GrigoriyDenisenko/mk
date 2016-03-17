<?php

namespace Framework\Controller;

use Framework\Response\Response;
use Framework\Renderer\Renderer;

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
        $this->request = $request;
        $class = get_called_class();
        echo $class;
        echo '<BR>Controller construct with request: ' . $request .'<BR>class: '.$class;
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
        echo '<BR>CONTROLLER renderer input layout: '. $Layout;
        echo '<BR>------DATA:-------<BR>';
        echo print_r($Data);
        $class = get_called_class();
        $ControllerName = str_replace('Controller','',basename(str_replace('\\', DIRECTORY_SEPARATOR, $class)));
        // Renderer-у нужно передать полный путь к шаблону
        // Возьмем его из названия модели ($Layout)
        //$renderer = new Renderer($layout, $content);
        //return new Response(Renderer::render("/../views/".$ControllerName."/".$Layout,$Data ));
        //$fullpath = realpath('...' . $layout);
        //$renderer = new Renderer('...'); // Try to define renderer like a service. e.g.: Service::get('renderer');
        //$content = $renderer->render($fullpath, $data);
        $content = Renderer::render($ControllerName."/".$Layout.".php",$Data);
        echo '<BR>------Content:-------<BR>';
        echo $content;
        return  new Response($content);
    }
}