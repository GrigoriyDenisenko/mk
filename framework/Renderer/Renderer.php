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
            // echo "<BR>main_template_file is set to: ".$main_template_file ;
        }else{
            // echo "Template NOT FOUND <BR>";
            // var_dump($config);
            throw new \Exception('File ' . $main_template_file . ' not found');
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
        // echo "<BR>RendererMain<BR> ";
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

        if (!file_exists($template_path)){
            $template_path=$this->templates_dir . $template_path;
        }

        //echo "<BR>Renderer->render with template <B>". $template_path ."</B> and data array:";
        //var_dump($data);

        // Подготовим функции, кот. вызываются из пользовательских шаблонов

        $include = function($controller_name, $action, Array $data = []) {
            //echo "<hr> include function from temlate file with controller name: ". $controller_name;
            return Service::get('app')->startController($controller_name, $action, $data);
        };

        $generateToken = function(){
            $token = md5('solt_string'.uniqid());
            setcookie('token', $token);
            echo '<input type="hidden" value="'.$token.'" name="token">';
        };

        $getRoute = function($name){
            return Service::get('router')->buildRoute($name);
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
            //echo "<HR>WRAP<BR>";
            $content = $this->renderMain($content);
        }

        return $content;
    }

    /**
     * рендерим страницу ошибки
     *
     * @param array $data
     *
     * $error_template_file
     */
    public function renderError($data = array()){
        return $this->render($this->error_template,$data);
    }

}