<?php
/**    
 * Application.php
 * 
 * PHP version 5
 *
 * @category   Category Name
 * @package    Package Name
 * @subpackage Subpackage name
 * @author     dimmask <ddavidov@mindk.com>
 * @copyright  2011-2013 mindk (http://mindk.com). All rights reserved.
 * @license    http://mindk.com Commercial
 * @link       http://mindk.com
 */


namespace Framework;

use Framework\Router\Router;

class Application {
    public function run(){
        $router = new Router(include('../app/config/routes.php'));
        $route =  $router->parseRoute($_SERVER['REQUEST_URI']);
        if(!empty($route)){
        } else {
        }
        // echo '<pre>';
        // echo 'Returned route: <BR>';
        print_r($route);
    }
}
