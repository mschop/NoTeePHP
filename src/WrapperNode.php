<?php

namespace NoTee;


class WrapperNode implements Node
{
    protected $children;

    public function __construct(array $children, Escaper $escaper)
    {
        foreach($children as &$child) {
            if(!is_object($child)) {
                $child = new TextNode((string)$child, $escaper);
            }
        }
        $this->children = $children;
    }

    public function __toString() : string
    {
        $result = '';
        foreach($this->children as $child) {
            $result .= (string)$child;
        }
        return $result;
    }

}