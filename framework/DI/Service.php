<?php

namespace Framework\DI;

/**
 * Class Service
 * Хранилище объектов и их состояний
 * реализация паттерна Dependency Injection - при создании объекта прокидываем в конструктор другой объект
 * за каждую функцию приложения отвечает один, условно независимый объект (сервис),
 * который может иметь необходимость использовать другие объекты (зависимости), известные ему интерфейсами.
 * Зависимости передаются (внедряются) сервису в момент его создания.
 *
 * @package Framework\DI
 */
class Service {

    protected static $services = array();

    public static function set($service_name, $obj){
        self::$services[$service_name] = $obj;
    }

    /**
     * Метод для получения зарегистрированного сервиса по имени
     * @param $serviceName имя сервиса
     * @return object - экземпляр сервиса, или null, если не зарегистрирован сервис с таким именем
     */
    public static function get($service_name){
        // ToDo: вместо null создать сервис
        return empty(self::$services[$service_name]) ? null : self::$services[$service_name];
    }
}