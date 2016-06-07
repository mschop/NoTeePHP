<?php

namespace NoTee;


use NoTee\Exceptions\InvalidOperationException;
use NoTee\Exceptions\PathOutdatedException;

class TextNode implements Node
{

    protected $text;

    public function __construct($text, Escaper $escaper)
    {
        $this->text = $escaper->escapeHtml($text);
    }

    public function __toString()
    {
        return $this->text;
    }

}
