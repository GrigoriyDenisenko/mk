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
    protected static $templates_dir = '';

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
            self::$main_template = $main_template_file;
            self::$error_template = $error_template_file;
            self::$templates_dir=dirname($main_template_file)."/";
            echo "<BR>main_template_file is set to: ".$main_template_file ;
        }else{
            echo "Template NOT FOUND ";

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

        //@TODO: set all required vars and closures..

        //$this->content = $content;
        // рендерим страницу из главного шаблона, внутрь которого
        // в переменную $content передаём ранее сгенерированный контент
        return self::render(self::$main_template, compact('content'), false);
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
            $template_path=self::$templates_dir . $template_path;
        }

        echo "<BR>Template: ". $template_path;

        extract($data); // Импортирует переменные из массива в текущую таблицу символов
        // @TODO: provide all required vars or closures...

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
            echo "WRAP-------------------";
            $content = self::renderMain($content);
        }

        return $content;
    }
}