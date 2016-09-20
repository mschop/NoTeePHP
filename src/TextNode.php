<?php

namespace NoTee;

class TextNode implements Node
{

    protected $text;

    public function __construct(string $text, Escaper $escaper)
    {
        $this->text = $escaper->escapeHtml($text);
    }

    public function __toString() : string
    {
        return $this->text;
    }

}
