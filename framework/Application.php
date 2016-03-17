<?php

namespace Framework;

use Framework\Controller\Controller;
use Framework\Router\Router;
use Framework\Renderer\Renderer;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;
use Framework\Exception\BadResponseTypeException;
use Framework\Exception\HttpNotFoundException;
use Framework\Exception\AuthRequredException;
use Framework\DI\Service;

class Application {

    /**
     * Конструктор фронт контроллера
     * @param $config_path string - путь к конфигурационному файлу
     */
    public function __construct($config_path)
    {
	    // echo 'Create APP with config: '. $config_path;
		// Берём значения из конфига APP и часто используемые инициализируем как сервисы

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


        //Service::set('renderer', ObjectPool::get('Framework\Renderer\Renderer', Service::get('config')));
		
/*         Service::set('config', include($config_path));
        Service::set('loader', 'Loader');
        Service::set('request', 'Framework\Request\Request');
 */
    }
	/**
	 * Запуск роутера, запуск нужного контроллера, отдает респонс
	 */	
    public function run(){
        // $router = new Router(include('../app/config/routes.php'));
		$router = Service::get('router');
        $route =  $router->parseRoute();
        // require_once('../src/Blog/Controller/PostController.php');
       
        // echo "<pre>";
        // echo "ROUTE:  <br />" ;
        // print_r($route);

        try{
        	if (!empty($route)){
                $controller_class = $route["controller"];
                // echo "<br> controller Class: " . $controller_class;
                $action = $route['action'] . 'Action';
                // echo "<br> with ACTION method: " .  $action ;
                // echo "<br> ---------- <br>";
                $controllerReflection = new \ReflectionClass($route['controller']);

				//$controller_class = $controller_class;
                if (!class_exists($controller_class)) {
                    throw new \Exception("Controller '$controller_class' not found ");
                } else {
					// проверяем, задан ли метод название+Action
                    if (!method_exists($controller_class, $action)) {
                        throw new \Exception("Controller '$controller_class' has no method '$action' ");
                    } else {
                        // Создадим CONTROLLER через рефлексию класса, описанного в конфиге роута
                        //$controllerReflection = new \ReflectionClass($controller_class);
       		            $controller = $controllerReflection->newInstance();
         		        // из задания в Action создаём новый экземпляр
       		            $actionReflection = $controllerReflection->getMethod($action);
                        // echo "<br>actionReflection: <br />";
                        // print_r($actionReflection);
                        //print_r($route['params']);
                        $params = $actionReflection->getParameters();
                        //Вызов метода (кот. описан в Action)
                        if(empty($params)) {
                            echo "<br> NO PARAMETERS on '$actionReflection' <br />";
                            //new Response('test');
                            $response = $actionReflection->invoke($controller);
                        } else {
							//  с передачей аргументов
     						// (Pоутер должен содержать массив 'params' взятый из URL)
                            // print_r($route['params']);
                            $response = $actionReflection->invokeArgs($controller, $route['params']);
                        }
       			        // $response = $actionReflection->invokeArgs($controller, $route['params']);
                        echo "<br> +++Response: <br />";
                        print_r($response);
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
         	} else {
        		throw new HttpNotFoundException('Route not found');
        	}
        }catch(HttpNotFoundException $e){
            // Render 404 or just show msg
            // HttpNotFoundException покажет 404 ошибку
            echo $e->getMessage();
        }
        catch(AuthRequredException $e){
        		echo 'Reroute to login page' ;
        	// Reroute to login page
        	//$response = new RedirectResponse(...);
            $e->getMessage();
        }
        catch(BadResponseException $e){
            echo $e->getMessage();
        }
        catch(\Exception $e){
        	// Do 500 layout...
        	echo $e->getMessage();
        }
        $response->send();
    }        
         //echo '<pre>';
         //echo 'Returned route: <BR>';
         //print_r($route);
}
