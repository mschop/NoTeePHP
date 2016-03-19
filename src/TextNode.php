<?php

namespace NoTee;


class TextNode implements HtmlNode
{

    private $text;

    public function __construct($text)
    {
        $this->text = htmlentities($text);
    }

    public function toString()
    {
        return $this->text;
    }

}
