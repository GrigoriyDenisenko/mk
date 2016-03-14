<?php


namespace Framework\Validation\Filter;

/**
 * Фильтр валидации для проверки, является ли стока заполненной
 * @package Framework\Validation\Filter
 */
namespace Framework\Validation\Filter;


class NotBlank implements ValidationFilterInterface {

    /**
     * Возврашает true, если переданное значение не является пустым
     * @param string $value строка для проверки
     * @return bool результат
     */
    public function isValid($value)
    {
        return !empty($value);
    }

    /**
     * Gets the error message by checking the value
     * @return string
     */
    public function getMessage()
    {
        return "The value must be not a blank";
    }
}