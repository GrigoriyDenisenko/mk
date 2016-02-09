<?php
/**
 * Загрузчик
 */
class Loader{

    /**
     * Статическая переменная, в которой мы будем хранить экземпляр класса @var Loader
     * Паттерн Singleton
     */
    protected static $instance = null;


	protected static $namespaces = array();

	public static function getInstance(){
        /**
         * Статическая функция, которая возвращает экземпляр класса или создает новый при необходимости
         * @return Loader
         */
        // проверяем актуальность экземпляра
		if(empty(self::$instance)){
            // создаем новый экземпляр
			self::$instance = new self();
		}
        // возвращаем созданный или существующий экземпляр
		return self::$instance;
	}

    private function __construct(){
        // Init
        // регистрация собственного автозагрузчика в стеке автозагрузки (вместо __autoload())
        // стек функций автозагрузки - это массив, элементами которого являются автозагрузчики.
        // Порядок автозагрузчиков соответствует порядку их регистрации, при помощи spl_autoload_register.
        // echo __CLASS__;
        spl_autoload_register(array(__CLASS__, 'load'));
        spl_autoload_register(array(__CLASS__, 'addNamespace'));
        // можно ещё добавить выгрузку через  spl_autoload_unregister()
    }


	public static function load($classname){
        // echo "--------load";
		$path = str_replace('Framework','',$classname);
		$path = __DIR__ . str_replace("\\","/", $path) . '.php';
        // может для windows использовать вместо "/" DIRECTORY_SEPARATOR
		if(file_exists($path)){
			include_once($path);
		}
	}

    public static function addNamespace($classname){
        //@TODO:
        foreach ($this->namespaces as $name => $dir_path){
            $pos = strpos($classname, $name);
            if($pos === 0){
                $class_path = str_ireplace($name, '', $classname);
                $path = $dir_path . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_path) . '.php';
                if(file_exists($path)){
                  include_once($path);
                }
            }
        }
    }

	private function __clone(){
        // lock
        /**
         * Закрываем доступ к функции вне класса.
         * Паттерн Singleton не допускает вызов этой функции вне класса
         **/
	}

	public static function addNamespacePath($namespace, $path){
        //@TODO:
        // echo "   namespace=".$namespace."," ;
        // echo "   path=".$path."," ;
        if(is_dir($path)){
            $namespace = rtrim($namespace,'\\');
            // echo "   new namespace=".$namespace."," ;
            self::$namespaces[$namespace] = $path;
        }
        // $ldr = self::getInstance();
        // $ldr->namespaces[$name] = $path;
	}

}

Loader::getInstance();
