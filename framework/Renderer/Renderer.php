<?php

namespace Framework\Renderer;

use Framework\DI\Service;

/**
 * Class Renderer
 * @package Framework\Renderer
 */
class Renderer {

    /**
     * @var string  Main wrapper template file location
     */

    protected $main_template = '';
    protected $error_template = '';
    protected $templates_dir = '';

    /**
     * Class instance constructor
     *
     * @param array $config
     * $main_template_file
     * $error_template_file
     */
    public function __construct($config = array()){
        // в app _construct через session создаем renderer с заданным шаблоном
        $main_template_file = realpath($config["main_layout"]);
        $error_template_file = realpath($config["error_500"]);

        if (file_exists($main_template_file)){
            $this->main_template = $main_template_file;
            $this->error_template = $error_template_file;
            $this->templates_dir=dirname($main_template_file)."/";
            echo "<BR>main_template_file is set to: ".$main_template_file ;
        }else{
            echo "Template NOT FOUND <BR>";
            var_dump($config);

            //$dir = Service::get('session')->get('path_to_view');
            //$this->layout = '../src/Blog/views/'.$dir.'/'.$layout.'.php';
        }
    }

    /**
     * Render main template with specified content
     *
     * @param $content
     *
     * @return html/text
     */
    public function renderMain($content){
        echo "<BR>RendererMain<BR> ";

        $flush = [];
        $user = Service::get('security')->getUser();

        // рендерим страницу из главного шаблона, внутрь которого
        // в переменную $content передаём ранее сгенерированный контент
        //$this->content = $content;
        return $this->render($this->main_template, compact('content', 'user', 'flush'), false);
    }

    /**
     * Render specified template file with data provided
     *
     * @param   string  Template file path (full)
     * @param   mixed   Data array
     * @param   bool    To be wrapped with main template if true
     *
     * @return  text/html
     */
    public function render($template_path, $data = array(), $wrap = true){
        echo "<BR>Renderer->render: ";

        if (!file_exists($template_path)){
            $template_path=$this->templates_dir . $template_path;

        }

        echo "<BR>Template: ". $template_path;

        $include = function($controller, $action, $args = array()) {
            $controllerInstance = new $controller();
            if ($args === null) {
                $args = array();
            }
            return call_user_func_array(array($controllerInstance, $action.'Action'), $args);
        };

        $generateToken = function(){
            $token = md5('solt_string'.uniqid());
            setcookie('token', $token);
            echo '<input type="hidden" value="'.$token.'" name="token">';
        };

        $getRoute = function($name){
            if( array_key_exists( $name, Service::get('routes'))) {
                $uri = Service::get('routes')[$name]['pattern'];
                echo $uri;
            }
        };

        extract($data); // Импортирует переменные из массива в текущую таблицу символов

        if (file_exists($template_path)) {
            //ob_start(PHP_OUTPUT_HANDLER_CLEANABLE); // Включение буферизации вывода
            ob_start();
            include( $template_path ); //выгружаем в буфер шаблон (html.php)
            $content = ob_get_contents(); //получаем содержимое текущего буфера в виде строки и очищаем его
            ob_end_clean();
            //echo $content;
        } else {
            throw new \Exception('File ' . $template_path . ' not found');
        }

        if($wrap){
            // наш шаблон находится внутри главного шаблона
            echo "<HR>WRAP<BR>";
            $content = $this->renderMain($content);
        }

        return $content;
    }
}