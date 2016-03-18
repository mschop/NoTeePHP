<?php

namespace NoTee;


class DefaultNode implements HtmlNode
{

    private $tagName;
    private $attributes;
    /** @var array-of-HtmlNode */
    private $children;

    private static $urlAttributes = [
        'action',
        'archive',
        'cite',
        'classid',
        'codebase',
        'data',
        'formaction',
        'href',
        'icon',
        'longdesc',
        'manifest',
        'poster',
        'src',
        'usemap',
    ];

    public function __construct($tagName, array $attributes = [], array $children = [])
    {
        if(!static::isValidTagName($tagName)) {
            throw new \InvalidArgumentException('Invalid tagName');
        }
        static::validateAttributes($attributes);
        $this->tagName = $tagName;
        $this->attributes = $attributes;
        $this->children = $children;
    }

    public function toString()
    {
        $childAmount = count($this->children);
        $attributeString = count($this->attributes) > 0 ? ' ' . $this->getAttributeString() : '';
        if($childAmount) {
            $result = '';
            /** @var HtmlNode $child */
            foreach($this->children as $child) {
                $result .= $child->toString();
            }
            return '<' . $this->tagName . $attributeString . '>' . $result . '</' . $this->tagName . '>';
        }
        return '<' . $this->tagName . $attributeString . ' />';
    }

    public function getAttributeString()
    {
        $allAttributes = [];
        foreach($this->attributes as $name => $value) {
            $this->isValidAttributeName($name);
            $escapedAttribute = $this->escapeAttribute($name, $value);
            $allAttributes[] = $name . '="' . $escapedAttribute . '"';
        }
        return implode(' ', $allAttributes);
    }

    private function escapeAttribute($name, $value)
    {
        if(in_array($name, static::$urlAttributes)) {
            return $value->toString();
        }
        return htmlspecialchars($value);
    }

    private static function validateAttributes($attributes)
    {
        foreach($attributes as $key => $value) {
            static::validateAttribute($key, $value);
        }
    }

    /**
     * @param $tagName
     * @return bool
     */
    private static function isValidTagName($tagName)
    {
        $regex = '/^[0-9a-z-_]*$/i';
        return preg_match($regex, $tagName) ? true : false;
    }

    /**
     * @param $attributeName
     * @return bool
     */
    private static function isValidAttributeName($attributeName)
    {
        $regex = '/^[0-9a-z-_]*$/i';
        return preg_match($regex, $attributeName) ? true : false;
    }

    private static function validateAttribute($key, $value)
    {
        if(!static::isValidAttributeName($key)){
            throw new \InvalidArgumentException("invalid attribute name $key");
        }
        if(in_array($key, static::$urlAttributes)) {
            if(!$value instanceof URLAttribute) {
                throw new \InvalidArgumentException("attribute $key has to be instance of URLAttribute");
            }
        }
    }

}
