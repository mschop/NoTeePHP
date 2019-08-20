<?php

namespace NoTee\Nodes;


use NoTee\EscaperInterface;
use NoTee\NodeInterface;

class WrapperNode implements NodeInterface
{
    protected array $children;

    public function __construct(array $children, EscaperInterface $escaper)
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