<?php
namespace Framework\Router;

use Framework\DI\Service;

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

        // переданный массив записывается в нашу карту маршрутов (protected array)
        // например:  возвращаемый из app\config\routes.php
        // echo "<pre>";
        // echo "----construct route MAP";
        self::$map = $routing_map;
        //print_r(self::$map);
    }

    /**
     * Parse URL
     * определим по нашей карте, что делать с переданным URL
     * @param $url - относительный путь
     */
    public function parseRoute($url = ''){

        //$url = empty($url) ? getUri$_SERVER['REQUEST_URI'] : $url;
        $url = empty($url) ? Service::get('request')->getUri() : $url;

        $route_found = null;
        //echo "<hr>parse Url: <B>".$url.'</B><br />';
        $url=preg_replace('~^\\/index.php$~i', '/', $url);

        foreach(self::$map as $route){
            // echo "---- Get pattern from MapArray:<br />";
            // print_r($route);
            $pattern = $this->prepare($route);
            // echo "Prepared pattern: ".$pattern.'<br />';

            if(preg_match($pattern, $url, $params)){
                // echo 'find route array and send to array $params: <br />';
                // print_r($params);
                // echo '---------------- <br />';
                // Get assoc array of params:
                preg_match($pattern, str_replace(array('{','}'), '', $route['pattern']), $param_names);
                // echo " get names of params:  <br />" ;
                // print_r($param_names);
                // $params[0] - теперь содержит всю переданную в URL строку
                // $params[1] - идентификатор (из {id})
                // каждый элемент массива $params обработаем через urldecode()
                $params = array_map('urldecode', $params);
                // и сопоставим имени параметра - его значение
                $params = array_combine($param_names, $params);
                // echo "param_names => param:  <br />" ;
                // print_r($params);
                array_shift($params); // Get rid of 0 element
                // первый элемент массива убрали, остался:
                // params[id] = "переданный_идентификатор"
                // echo " after shifting:  <br />" ;
                // print_r($params);
                $route_found = $route;
                $route_found['params'] = $params;
                // к карте MapArray добавился елемент ['params'] со значением переданного идентификатора
                break;
            }
            /* 			else{
                            echo $pattern." # ".$url.'<br />';
                        }
             */
        }
        return $route_found;
    }

    /**
     * Build URL from Route map
     * @param $route_name соответствует маршруту запроса,
     * @param $params является списком параметров для добавления к URL
     * По умолчанию, адреса создаются посредством buildRoute в относительном формате.
     * Например, при значениях параметров $route_name='edit_post' и $params=array('id'=>100),
     * получим такой URL: /posts/100/edit  (взято из маршрута /posts/{id}/edit)
     * $new_url = $this->buildRoute('show_post',array('id'=>100));
     * echo "Buid URL from show_post: ". $new_url;
     * @return $url  - относительный путь, кот. можно передать параметром в parseRoute
     */
    public function buildRoute($route_name, $params = array()){

        $routes = self::$map[$route_name];
        //$this->getRoutesByNames(array($route_name));
        if (empty($routes)) {
            // throw new RouteNotFoundException(sprintf('Route "%s" does not exist.', $route_name));
            // echo sprintf('Route "%s" does not exist.', $route_name);
            return '';
        }
        //echo 'From ROUTE:<br />';
        //var_dump($routes);
        $url='';

        if ($routes['_requirements']) {
            // строка URL должна содержать рекомендуемые параметры
            // echo "<br />Get params of route:  <br />" ;
            $pattern=$routes['pattern'];
            foreach ($params as $key => $value) {
                $pattern = str_replace('{' . $key . '}', $value, $pattern);
            }
            // @TODO: можно доделать если параметр не передали...
            /*          	foreach ($routes['_requirements'] as $key => $requirement) {
                            $pattern = str_replace('{' . $key . '}', 'all', $pattern);
                        }
             */
            $url=$pattern;


        }
        else {
            $url=$routes['pattern'];
        }

        return $url;

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