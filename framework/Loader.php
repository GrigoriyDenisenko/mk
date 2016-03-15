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
     
    // массив - сисок дирректорий используемых как пространство имён
	// ключи массива содержат префикс пространства имён
	private static $namespaces = array();

    public static function getInstance(){
        /**
        * Статическая функция, которая возвращает экземпляр класса или создает новый при необходимости
        * @return Loader
        */
        // проверяем актуальность экземпляра
        if(empty(self::$instance)){
          // создаем новый (и единственный) экземпляр себя
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
		// вызов framework/Loader.php регистрирует загрузчик 
        // в данном случае мы перевели вызовы на себя (__CLASS__ = Loader)
        spl_autoload_register(array(__CLASS__, 'load'));
		// $obj = new $ClassName теперь пойдёт через Loader::load($ClassName)
		
        // зарегистрируем Namespace фреймворка
        self::addNamespacePath("Framework\\", __DIR__);
        // spl_autoload_register(array(__CLASS__, 'addNamespace'));
        // можно ещё добавить выгрузку через  spl_autoload_unregister()
    }

	/**
     * Загружаем переданный через переменную $classname класс.
     *
     * @param string $className вызываемый класс
	 * $app = new \Framework\ClassDir\CassName - 
	 * передаст \Framework\ClassDir\CassName через $classname
	 * загрузится CassName.php находяшийся ниже текущего каталога
	 * /var/www.../текущий_каталог="Framework"/ClassDir/CassName.php
     */
    
    public static function load($className){
        echo '<pre>';
        echo "load ".$className."\n";
		
		$namespaceName = strtok($className, "\\");
        // echo "$namespaceName=" . $namespaceName;
		
		if(array_key_exists($namespaceName, self::$namespaces)) {
            $namespacePath = self::$namespaces[$namespaceName];
            // echo "namespacePath" . $namespacePath;
            $path = str_replace("\\", DIRECTORY_SEPARATOR, $namespacePath . str_replace($namespaceName, "", $className)) . ".php";
            if (file_exists($path)) {
                // echo '<pre>';
                // echo "include_once ".$path;
                include_once($path);
            }
		}
    }
    
	/**
     */
    public static function addNamespace($classname){
        //@TODO:
        foreach (self::$namespaces as $name => $dir_path){
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

	/**
    * добавляем в массив соответствие физических директорий - префиксу пространства имён.
    *
    * @param string $namespace - префикс пространства имён.
    * @param string $path - директория для файлов классов из пространства имён.
    */
    public static function addNamespacePath($namespace, $path){
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
