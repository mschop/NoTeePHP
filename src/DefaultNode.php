<?php

namespace NoTee;



class DefaultNode implements Node
{
    public static $validateAttributes = true;
    public static $validateAttributeNames = true;

    protected $tagName;
    protected $escaper;
    protected $attributes;
    /** @var array-of-HtmlNode */
    protected $children;

    public function __construct($tagName, Escaper $escaper, array $attributes = [], array $children = [])
    {
        $this->tagName = $tagName;
        $this->escaper = $escaper;
        $this->attributes = $attributes;
        foreach($children as &$child) {
            if(is_string($child)) {
                $child = new TextNode($child, $this->escaper);
            }
        }
        $this->children = $children;
    }

    public function __toString()
    {
        $attributeString = !empty($this->attributes) ? ' ' . $this->getAttributeString() : '';
        if(isset($this->children[0]) || $this->tagName === 'script') {
            $result = '';
            /** @var Node $child */
            foreach($this->children as $child) {
                $result .= $child;
            }
            return '<' . $this->tagName . $attributeString . '>' . $result . '</' . $this->tagName . '>';
        }
        return '<' . $this->tagName . $attributeString . ' />';
    }

    public function getAttributeString()
    {
        $attributeString = '';
        $first = true;
        foreach($this->attributes as $name => $value) {
            $escapedAttribute = $this->escapeAttribute($value);
            $attributeString .= ($first ? '' : ' ') . $name . '="' . $escapedAttribute . '"';
            $first = false;
        }
        return $attributeString;
    }

    private function escapeAttribute($value)
    {
        if(is_object($value)) {
            return (string)$value;
        }
        return $this->escaper->escapeAttribute($value);
    }

}
