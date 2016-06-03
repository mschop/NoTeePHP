<?php


namespace NoTee;


class RawAttributeValue implements AttributeValue
{
    private $raw;

    public function __construct($value)
    {
        $this->raw = $value;
    }

    public function __toString()
    {
        return $this->raw;
    }
}
