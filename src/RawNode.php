<?php

namespace NoTee;

class RawNode implements Node
{
    protected $raw;

    public function __construct(string $value)
    {
        $this->raw = $value;
    }

    public function __toString() : string
    {
        return $this->raw;
    }
}
