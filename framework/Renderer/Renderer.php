<?php

namespace Framework\Renderer;

/**
 * Class Renderer
 * @package Framework\Renderer
 */
class Renderer {

    /**
     * @var string  Main wrapper template file location
     */

    protected static $main_template = '';
    protected static $error_template = '';

    /**
     * Class instance constructor
     *
     * @param array $config
     * $main_template_file
     * $error_template_file
     */
    public function __construct($config = array()){
        // в app _construct через session создаем renderer с заданным шаблоном
        $main_template_file = $config["main_layout"];
        $error_template_file = $config["error_500"];
        if (file_exists($main_template_file)){
            //$main_template ='';
            self::$main_template = $main_template_file;
            self::$error_template = $error_template_file;
            echo " main_template_file is set to ".$main_template_file ;
        }else{
            echo "Template NOT FOUND ";

            //$dir = Service::get('session')->get('path_to_view');
            //$this->layout = '../src/Blog/views/'.$dir.'/'.$layout.'.php';
        }

		
        //$this->main_template = $main_template_file;
    }

    /**
     * Render main template with specified content
     *
     * @param $content
     *
     * @return html/text
     */
    public function renderMain($content){

        //@TODO: set all required vars and closures..

        $this->content = $content;

 /*       if (file_exists($layout)){
            $this->layout = $layout;
        }else{
            $dir = Service::get('session')->get('path_to_view');
            $this->layout = '../src/Blog/views/'.$dir.'/'.$layout.'.php';
        }*/

        return $this->render($this -> $main_template, compact('content'), false);
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

        extract($data); // Импортирует переменные из массива в текущую таблицу символов
        // @TODO: provide all required vars or closures...

        ob_start(); // Включение буферизации вывода
        include( $template_path ); //подключаем шаблон который задаётся в application
        $content = ob_end_clean(); //Получить содержимое текущего буфера и удалить его а затем вернуть

        if($wrap){
			// наш шаблон находится внутри главного шаблона
            $content = $this->renderMain($content);
        }

        return $content;
    }
}