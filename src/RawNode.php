<?php

namespace NoTee;


use NoTee\Exceptions\InvalidOperationException;
use NoTee\Exceptions\PathOutdatedException;

class RawNode implements Node
{

    protected $raw;

    public function __construct($value)
    {
        $this->raw = $value;
    }

    public function __toString()
    {
        return $this->raw;
    }

}
