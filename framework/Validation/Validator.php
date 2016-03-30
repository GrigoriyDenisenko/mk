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
    protected $fields;
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
        $this->fields = get_object_vars($ActiveRecordObj);
        //$this->fields = $ActiveRecordObj->getFields();
        $this->rules = $ActiveRecordObj->getRules();
    }

    /**
     * Validate fields of ActiveRecord object
     *
     * @return bool True if all fields of ActiveRecord object are valid
     */
    public function isValid()
    {
        $final_validation_result = true; // Use the default validation behavior if the validation rules are absent

        foreach ($this->rules as $name => $filters) {
            if (array_key_exists($name, $this->fields)) {
                foreach ($filters as $rule) {
                    $result = $rule->isValid($this->fields[$name]);
                    if ($result === false) {
                        $this->_errors[$name] = ucfirst($name) . ' validation error. '.$rule->getMessage();
                        $final_validation_result = false;
                    }
                }
            }
        }

        // Store filled post fields in session to show them in renderer and give user a chance to correct them
        if ($final_validation_result === false) {
            Service::get('session')->savePost($this->fields);
        }

        return $final_validation_result;
    }

    public function getErrors()
    {
        return $this->_errors;
    }
}
