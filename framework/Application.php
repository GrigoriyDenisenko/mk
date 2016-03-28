<?php

namespace Framework;

use Framework\Controller\Controller;
use Framework\Router\Router;
use Framework\Renderer\Renderer;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;
use Framework\Exception\BadResponseTypeException;
use Framework\Exception\HttpNotFoundException;
use Framework\Exception\NotAuthException;
use Framework\DI\Service;
use Framework\Security\Security;
use Framework\Session\Session;
use Framework\Request\Request;

class Application {

    /**
     * Конструктор фронт контроллера
     * @param $config_path string - путь к конфигурационному файлу
     */
    public function __construct($config_path)
    {
        // echo 'Create APP with config: '. $config_path;
        // Берём значения из конфига APP и часто используемые инициализируем как сервисы

        Service::set('security', new Security());
        Service::set("session",new Session());
        Service::set('request', new Request());
        $config = include_once $config_path;

        // из значения 'routes' получаем путь к таблице маршрутов
        // подгружаем роутер и инициализируем таблицу маршрутизации
        Service::set("router", new Router($config["routes"]));
        //Service::set('renderer', new Renderer($config["main_layout"]));
        // в renderer передадим весь конфиг, а там возьмем пути к view
        //Service::set('renderer','Framework\Renderer\Renderer');
        Service::set('renderer', new Renderer($config));
        try{
            // получим подключение к базе
            // extract(Service::get('config')['pdo']);
            // $dns .= ';charset=utf8';
            // $db = new \PDO($dns, $user, $password);
            // Service::set('db', $db);
            Service::set('db', new \PDO($config['pdo']['dsn'], $config['pdo']['user'], $config['pdo']['password']));
        }catch(\PDOException $e){
            echo $e->getMessage();die();
        }
        Service::set('app', $this);
    }
    /**
     * Запуск роутера, запуск нужного контроллера, отдает респонс
     */
    public function run(){
        $router = Service::get('router');
        $route =  $router->parseRoute();
        //echo "<pre>ROUTE:  <br />";
        //var_dump($route);
        if (!empty($route)) {
            // Нашли маршрут, подготовимся к запуску контроллера
            // $controller_class = $route["controller"];
            // $action = $route['action'];
            // (Pоутер должен содержать массив 'params' взятый из URL, создается в методе parseRoute)
            // print_r($route['params']);
            // контроллер запустился, запишем откуда стартовали
            if ($user = Service::get('security')->getUser()) {  // Check the user role on the basis of user data stored in session
                $user_role = is_object($user) ? $user->getRole() : $user['role'];
            }
            //Service::get('session')->returnUrl = $route['pattern'];
            return $this->startController($route["controller"], $route['action'], $route['params']);
        }else{
            //throw new \Exception($_SERVER['REQUEST_URI'] . ' not found');
            //$e = new HttpNotFoundException(ltrim($_SERVER['REQUEST_URI'],'/') . ' not found');
            //$response = new Response(Service::get('renderer')->renderError($e));
            //$response->send();
            // пользователь преднамеренно зашел на отсутствующий url
            echo ltrim($_SERVER['REQUEST_URI'],'/') . ' not found';
        }
    }

    /**
     * По заданному контроллеру выполняем метод action с параметрами, переданными через массив.
     *
     * @param string $controller_name
     * @param string $action
     * @param array $data
     *
     * @return Response|null
     * @throws \Exception If obtained controller is not subclass of Controller class
     */
    /**
     * Method starts necessary method of necessary controller with help of Reflection
     *
     * @param string $controller_name
     * @param string $action
     * @param array $data
     * @throws HttpNotFoundException
     * @throws \Exception
     *
     * @return object
     */
    public function startController($controller_name, $action, Array $data = []) {
        try {
            if (!class_exists($controller_name)) {
                throw new \Exception("Controller '$controller_name' not found ");
            } else {
                // проверяем, задан ли метод название+Action
                $action = $action . 'Action';
                if (!method_exists($controller_name, $action)) {
                    throw new \Exception("Controller '$controller_name' has no method '$action' ");
                } else {
                    // Создадим экземпляр CONTROLLER через рефлексию класса
                    $controllerReflection = new \ReflectionClass($controller_name);
                    //echo "<hr> Reflection: <br>";
                    //var_dump($controllerReflection);
                    $controller = $controllerReflection->newInstance();
                    $actionReflection = $controllerReflection->getMethod($action);
                    // echo "<br>actionReflection: <br />";
                    // print_r($actionReflection);
                    //print_r($route['params']);
                    $params = $actionReflection->getParameters();
                    //Вызов метода (кот. описан в Action)
                    if(empty($params)) {
                        // echo "<br> NO PARAMETERS on '$actionReflection' <br />";
                        // new Response('test');
                        $response = $actionReflection->invoke($controller);
                    } else {
                        //  с передачей аргументов
                        $response = $actionReflection->invokeArgs($controller, $data);
                        // $response = $actionReflection->invokeArgs($controller, $route['params']);
                    }
                    //echo "<HR>Response from: " . $actionReflection;
                    //var_dump($response);
                    // Если ответ пришел в виде класса - экземпляра экземпляра Response
                    if ($response instanceof Response){
                        // Значит всё нормально - пришел правильный ответ
                        // echo "<br> +++Response: <br />";
                        // print_r($response);

                    } else {
                        throw new BadResponseTypeException('Ooops');
                    }
                }
            }

        }
            //catch(HttpNotFoundException $e){
            // Render 404 or just show msg
            // HttpNotFoundException покажет 404 ошибку
            //echo $e->getMessage();
            //}
        catch(NotAuthException $e){
            // Service::get('session')->set('returnUrl', Registry::getConfig('route')['pattern']);
            // echo 'Reroute to login page';
            // $e->getMessage();
            $response = new ResponseRedirect(Service::get('router')->buildRoute('login'));
        }
            //catch(BadResponseException $e){
            //echo $e->getMessage();
            //}
        catch(\Exception $e){
            // Do 500 layout...
            echo $e->getMessage();
            //$renderer = new Renderer($e->layout, array('message'=>$e->message, 'code'=>$e->code));
            //$response = new Response($renderer->render());
            $response = new Response(Service::get('renderer')->renderError($e), $e->getCode());
        }
        $response->send();
    }

    public function getActionResponse($controller_name, $action, Array $data = [])
    {
        $action .= 'Action';

        $controllerReflection = new \ReflectionClass($controller_name);

        if (!$controllerReflection->isSubclassOf('Framework\Controller\Controller')) {
            throw new \Exception("Unknown controller " . $controllerReflection->name);
        }

        if ($controllerReflection->hasMethod($action)) {
            // ReflectionMethod::invokeArgs() has been overloaded in class ReflectionMethodNamedArgs
            // Now it provides controller action invoking with named arguments
            $actionReflection = new ReflectionMethodNamedArgs($controller_name, $action);
            $controller = $controllerReflection->newInstance();
            $response = $actionReflection->invokeArgs($controller, $data);
            return $response;
        }
        return null;
    }
}