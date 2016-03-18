<?php

namespace NoTee;


class Raw implements URLAttribute
{

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function toString()
    {
        return $this->value;
    }

}
