<?php

namespace Framework\Validation;

use Framework\DI\Service;
use Framework\Model\ActiveRecord;


/**
 * Class Validator
 *
 * @package Framework\Validation
 */
class Validator
{
    protected $fiels;
    protected $rules;
    protected $_errors = [];

    /**
     * Validator constructor.
     * Get ActiveRecord object.
     *
     * @param ActiveRecord $ActiveRecordObj
     */
    public function __construct($ActiveRecordObj)
    {
        $this->fiels = $ActiveRecordObj->getFields();
        echo "<HR>Fields:";
        var_dump($this->fiels);
        $this->rules = $ActiveRecordObj->getRules();
        echo "<HR>Rules:";
        var_dump($this->rules);
    }

    /**
     * Validate fields of ActiveRecord object
     *
     * @return bool True if all fields of ActiveRecord object are valid
     */
//    public function isValid()
//    {
//        $final_validation_result = true; // Use the default validation behavior if the validation rules are absent
//
//        //$fields = $this->_model->getFields();
//        //$all_rules = $this->_model->getRules();
//
//        foreach ($this->rules as $name => $filters) {
//            if (array_key_exists($name, $this->fiels)) {
///*                foreach ($rules as $rule) {
//                    $valid = $rule->isValid($fields[$name]);
//                    if ($valid === false) {
//
//                        $this->_errors[$name] = ucfirst($name) . ' validation error';
//                        $final_validation_result = false;
//                    }
//                }*/
//            }
//        }
//
//        // Store filled post fields in session to show them in renderer and give user a chance to correct them
//        if ($final_validation_result === false) {
//            //Service::get('session')->setPost($this->_model);
//        }
//
//        return $final_validation_result;
//    }

    public function getErrors()
    {
        return $this->_errors;
    }
}
