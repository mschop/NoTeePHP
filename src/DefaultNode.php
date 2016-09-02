<?php

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

    /**
     * DefaultNode constructor.
     * @param string $tagName
     * @param Escaper $escaper
     * @param array $attributes
     * @param array $children
     */
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

    /**
     * @return string
     */
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

    /**
     * @param string $value
     * @return string
     */
    protected function escapeAttribute($value)
    {
        return $this->escaper->escapeAttribute($value);
    }

}
