<?php


namespace Framework\Validation\Filter;


class Length implements ValidationFilterInterface {

    protected $min;
    protected $max;

    /**
     * @param $min
     * @param $max
     */
    public function __construct($min, $max){
        $this->min = $min;
        $this->max = $max;
    }

    public function isValid($value){

        return (strlen($value)>=$this->min) && (strlen($value)<=$this->max);
    }

    /**
     * Gets the error message by checking the value
     */
    public function getMessage()
    {
        return 'must be less than '.$this->max.' and more than '.$this->min.' characters';
    }
}