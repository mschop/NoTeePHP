<?php

namespace NoTee;


class AttributeValidator
{

    private $useValueValidation;
    private $useNameValidation;

    /**
     * AttributeValidator constructor.
     * @param $useValueValidation
     * @param $useNameValidation
     */
    public function __construct($useValueValidation, $useNameValidation)
    {
        $this->useValueValidation = $useValueValidation;
        $this->useNameValidation = $useNameValidation;
    }

    public function isValid($key, $value)
    {
        if($this->useNameValidation) {
            $regex = '/^[0-9a-z-_]*$/i';
            if(!preg_match($regex, $key)) {
                return false;
            }
        }

        if(
            $this->useValueValidation
            && array_key_exists($key, Attribute::$urlAttributes)
            && !$value instanceof URLAttribute
        ) {
            return false;
        }

        return true;
    }

}
