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

    protected $main_template  = '';
    protected $error_template = '';
    protected $templates_dir  = '';
    protected $txt_reclama    ='';
    private $content;

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
            $this->txt_reclama='<a style="color: LightGray" href="'.$config["reclama_lnk"].'">'.
                $config["reclama_txt"].'</a>';
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
    private function renderMain(){
        //public function renderMain($content){
        //echo "<BR>RendererMain<BR> ";
        $flush = Service::get('session')->getFlash();
        $user = Service::get('security')->getUser();
        if (!empty($this->txt_reclama)){
            $reclama = '<br/><br/>'.$this->txt_reclama;
        }
        $content=$this->content;
        //echo "Get USER: ";
        //var_dump($user);
        //$this->content = $content;
        // рендерим страницу из главного шаблона, внутрь которого
        // через ассоциативный массив передаём
        // content - ранее сгенерированный контент
        // user - параметры текущего пользователя
        // flush - сессионные сообщения
        return $this->render($this->main_template, compact('content', 'user', 'flush', 'reclama'), false);
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
            // если не указан полный путь к файлу(или по данному пути файл не найден), возьмем из конфига путь к шаблонам
            $template_path=$this->templates_dir . $template_path;
        }

        //echo "<BR>Renderer->render with template <B>". $template_path ."</B> and data array:";
        //var_dump($data);

        //echo '<hr>action: '.$action;
        // Подготовим функции, кот. вызываются из пользовательских шаблонов

        $include = function($controller_name, $action, Array $data = []) {
            //echo "<hr> include function from temlate file with controller name: ". $controller_name;
            return Service::get('app')->startController($controller_name, $action, $data);
        };

        // сгенерированный токен внедряется в страницу скрытым полем для последующей передачи через пост
        $generateToken = function(){
            $token = Service::get('security')->generateToken();
            //echo "hidden token:";
            //var_dump($token);
            echo '<input type="hidden" value="'.$token.'" name="token">';
        };

        /*$generateToken = function(){
            $token = md5('solt_string'.uniqid());
            setcookie('token', $token);
            echo '<input type="hidden" value="'.$token.'" name="token">';
        };*/

        $getRoute = function($name){
            return Service::get('router')->buildRoute($name);
        };

        //echo "post1:";
        //var_dump($post);

        extract($data); // Импортирует переменные из массива в текущую таблицу символов

        //echo "post2:";
        //var_dump($post);
        if (empty($post)) {
            // возьмем из сессии сохраненные данные не прошедшие валидацию
            $post = Service::get('session')->getFromPost();
            // теперь данные массива $post можно использовать в шаблоне через @$post->имя_поля
            //echo "post3:";
            //var_dump($post);
        }else{
            // если мы разворачиваем заданный пост пользователя:
            if (array_key_exists('id', $post) && Service::get('security')->isAuthenticated()){
                //&&!empty($post['content']
                //echo "post model:";
                //var_dump($post);
                //echo "<hr>data:";
                //var_dump ($data);
                if (empty($data['edit'])) {
                    // если это не режим редактирования, то добавляется кнопка редактирования
                    $footer = '<br/><a href="/posts/' . $post->id . '/edit">Edit post</a>';
                }else{
                    $footer='';
                }
                $footer = $footer. '<br/><a style="color: red" href="/posts/' . $post->id . '/delete">Delete post</a>';

//                $footer= '<br/><a href="/posts/' . $post->id . '/edit">Edit post</a><br/>
//                  <a style="color: red" href="/posts/' . $post->id . '/delete">Delete post</a>';
            }
        }
        if (file_exists($template_path)) {
            //ob_start(PHP_OUTPUT_HANDLER_CLEANABLE); // Включение буферизации вывода
            if (!empty($reclama)){
                $footer .= $reclama;
            }
            //ob_start(PHP_OUTPUT_HANDLER_CLEANABLE);
            ob_start(null, 0, PHP_OUTPUT_HANDLER_CLEANABLE | PHP_OUTPUT_HANDLER_REMOVABLE);
            //ob_start();
            include( $template_path ); //выгружаем в буфер шаблон (html.php)
            $this->content = ob_get_contents().$footer; //получаем содержимое текущего буфера в виде строки и очищаем его
            //$content = ob_get_contents().$footer; //получаем содержимое текущего буфера в виде строки и очищаем его
            //$content = ob_get_clean().$footer; //получаем содержимое текущего буфера в виде строки и очищаем его
            if (!ob_end_clean()){
                ob_end_clean();
            }
            while (ob_get_length()) {
                //echo '<hr>!!!!!!!!';
                ob_end_clean();
            }
            //echo $content;
        } else {
            throw new \Exception('File ' . $template_path . ' not found');
        }

        if($wrap) {
            // наш шаблон находится внутри главного шаблона
            //echo "<HR>WRAP<BR>";
//            if (!empty($this->txt_reclama)){
//                $content .= '<br/><br/>'.$this->txt_reclama;
//            }
            //echo $content;
            //$content = $this->renderMain($content);
            $this->content = $this->renderMain();
        }
        return $this->content;
        //return $content;
    }

    /**
     * рендерим страницу ошибки
     *
     * @param array $data
     *
     * $error_template_file
     */
    public function renderError($data = array()){
        //echo "<hr>Err:";
        //var_dump($data);
        return $this->render($this->error_template,$data);
    }

}