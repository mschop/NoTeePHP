<?php


namespace NoTee;


class RawAttribute implements URLAttribute
{
    private $raw;

    public function __construct($value)
    {
        $this->raw = $value;
    }

    public function toString()
    {
        return $this->raw;
    }
}
