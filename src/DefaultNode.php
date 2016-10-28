<?php

declare(strict_types=1);

namespace NoTee;



class DefaultNode implements Node
{
    protected static $voidTags = [
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input',
        'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'
    ];

    protected $tagName;
    protected $escaper;
    protected $attributes;
    /** @var array-of-HtmlNode */
    protected $children;

    public function __construct(string $tagName, Escaper $escaper, array $attributes = [], array $children = [])
    {
        $this->tagName = $tagName;
        $this->escaper = $escaper;
        $this->attributes = $attributes;
        foreach($children as &$child) {
            if(!is_object($child)) {
                $child = new TextNode((string)$child, $this->escaper);
            }
        }
        $this->children = $children;
    }

    public function __toString() : string
    {
        $attributeString = !empty($this->attributes) ? ' ' . $this->getAttributeString() : '';
        if(isset($this->children[0]) || !in_array($this->tagName, static::$voidTags)) {
            $result = '';
            /** @var Node $child */
            foreach($this->children as $child) {
                $result .= $child;
            }
            return '<' . $this->tagName . $attributeString . '>' . $result . '</' . $this->tagName . '>';
        }
        return '<' . $this->tagName . $attributeString . ' />';
    }

    public function getAttributeString() : string
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

    protected function escapeAttribute(string $value) : string
    {
        return $this->escaper->escapeAttribute($value);
    }
}
