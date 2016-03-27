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
        $class = get_called_class();
        //new Response("test");
        //echo '<hr>CONTROLLER renderer input layout: <B>'. $Layout .'</B> from class: '.$class.' with DATA array:<BR>';
        //echo var_dump($Data);
        $ControllerName = str_replace('Controller','',basename(str_replace('\\', DIRECTORY_SEPARATOR, $class)));
        // Renderer-у нужно передать полный путь к шаблону
        // Возьмем его из названия модели ($Layout)
        //$renderer = new Renderer($layout, $content);
        //return new Response(Renderer::render("/../views/".$ControllerName."/".$Layout,$Data ));
        //$fullpath = realpath('...' . $layout);
        //$renderer = new Renderer('...'); // Try to define renderer like a service. e.g.: Service::get('renderer');
        //$content = $renderer->render($fullpath, $data);
        $content = Service::get('renderer')->render($ControllerName."/".$Layout.".php",$Data);
        //echo '<HR><B>Content:</B><BR>';
        //echo $content;
        return  new Response($content);
    }

    /**
     * Метод выполняет редирект по заданному адресу с заданным сообщением
     * @param string $uri для редиректа
     * @param string $message сообщение редиректа (будет отправлено как GET пара
     * @return ResponseRedirect респонс-редирект на заданный uri с заданным сооб
     */
    /**
     * Redirect to specified URL via a Location header.
     *
     * @param   string $url The URL to redirect
     * @param   string|null $message The message for flush if any
     * @param   int $code The redirect status code
     *
     * @return  ResponseRedirect
     */
    public function redirect($uri, $message = null, $code = 302)
    {
        if (empty($uri)) {
            //throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
            $uri = '/';
        }
        if (isset($message)) Service::get('session')->setFlash('info', $message);
        return new ResponseRedirect($uri, $code);
    }

    /**
     * Метод возвращает реквест
     * @return Request реквест
     */

    public function getRequest() {
        return Service::get('request');
    }

    /**
     * Возвращает путь по заданному имени роута и параметрам
     * @param string $route_name имя роута как в конфиге
     * @param array $params необязательный параметр - ассоциативный массив в формате имя переменной => значение
     * @return string uri согласно паттерну заданного роута с учетом значений параметров. Если роут не найден - вернется значение /
     */
    public function generateRoute($route_name, $params = array())
    {
        return Service::get("router")->buildRoute($route_name, $params);
    }

}