<?php
namespace Framework\Router;
/**
 * Router.php
 */
class Router{
    /**
     * @var array
	 * массив - карта маршрутов, кот. формируется 
	 * при создании объекта из класса Router
     */
    protected static $map = array();
    /**
     * Class construct
     */
    public function __construct($routing_map = array()){
		
		// переданный массив записывается в нашу карту маршрутов
		// например: app\config\routes.php
		echo "construct route";
        self::$map = $routing_map;
    }
    /**
     * Parse URL
     * определим по нашей карте, что делать с переданным URL
     * @param $url
     */
    public function parseRoute($url = ''){
		
		$url = empty($url) ? $_SERVER['REQUEST_URI'] : $url;
        
		$route_found = null;
        echo "<pre>";
        echo "parse Url: ".$url.'<br />';
		$url=preg_replace('~^\\/index.php$~i', '/', $url);
		
        foreach(self::$map as $route){
			// echo "---- Get pattern from MapArray:<br />";
			// print_r($route);
            $pattern = $this->prepare($route);
			// echo "Prepared pattern: ".$pattern.'<br />';
		
            if(preg_match($pattern, $url, $params)){
                // echo 'find and send to array $params: <br />';
     			// print_r($params);
                // Get assoc array of params:
                preg_match($pattern, str_replace(array('{','}'), '', $route['pattern']), $param_names);
				// echo " get names of params:  <br />" ;
     			// print_r($param_names);
				// $params[0] - теперь содержит всю переданную в URL строку
				// $params[1] - идентификатор (из {id})
				// каждый элемент массива $params обработаем через urldecode()
                $params = array_map('urldecode', $params);
                $params = array_combine($param_names, $params);
                array_shift($params); // Get rid of 0 element
				// первый элемент массива убрали, остался:
				// $params[id] = "переданный идентификатор"
				// echo " after shifting:  <br />" ;
     			// print_r($params);
                $route_found = $route;
                $route_found['params'] = $params;
				// к карте MapArray добавился елемент ['params'] со значением переданного идентификатора
                break;
            }
			else{ echo $pattern." # ".$url;}
        }
        return $route_found;
    }
    public function buildRoute($route_name, $params = array()){
        // @TODO: Your code...
    }
    private function prepare($route){
		// "{набор_символов}" ("{id}") - меняем на "([\w\d_]+)" (любая последовательность букв, цифр и подчеркивания)
        $pattern = preg_replace('~\{[\w\d_]+\}~Ui','([\w\d_]+)', $route['pattern']);
        $pattern = '~^'. $pattern.'$~';
        return $pattern;
    }
//    private function chunkVars(){
//   		$route_pattern = self::routeToRegexp($route['route']);
//
//    }
}