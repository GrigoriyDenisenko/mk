<?php

namespace Framework;

use Framework\Router\Router;
use Framework\Exception\HttpNotFoundException;
use Framework\Response\Response;

class Application {
	
	/**
	 * Запуск роутера, запуск нужного контроллера, отдает респонс
	 */	
    public function run(){
        $router = new Router(include('../app/config/routes.php'));
        $route =  $router->parseRoute();

        require_once('../src/Blog/Controller/PostController.php');
        try{
        	if(!empty($route)){
             	// Если роутер запустился, пропускаем его через рефлексию 
        		$controllerReflection = new \ReflectionClass($route['controller']);
        		$action = $route['action'] . 'Action';
        		if($controllerReflection->hasMethod($action)){
        			// из задания в Action создаём новый экземпляр
        			$controller = $controllerReflection->newInstance();
        			$actionReflection = $controllerReflection->getMethod($action);
                    //Вызов метода (кот. описан в Action) с передачей аргументов (Описанных в params)
        			$response = $actionReflection->invokeArgs($controller, $route['params']);
                    // Если ответ пришел в виде класса - экземпляра экземпляра Response
        			if($response instanceof Response){
        				// Значит всё нормально - пришел правильный ответ
        			} else {
        				throw new BadResponseTypeException('Ooops');
        			}
        		}
        	} else {
        		throw new HttpNotFoundException('Route not found');

        	}
        }catch(HttpNotFoundException $e){
        	// Render 404 or just show msg
            // HttpNotFoundException покажет 404 ошибку
            $e->getMessage();
        }
        catch(AuthRequredException $e){
        	// Reroute to login page
        	//$response = new RedirectResponse(...);
            $e->getMessage();
        }
        catch(\Exception $e){
        	// Do 500 layout...
        	echo $e->getMessage();
        }
        $response->send();
    }        
        // echo '<pre>';
        // echo 'Returned route: <BR>';
        // print_r($route);
}
