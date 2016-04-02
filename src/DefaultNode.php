<?php

namespace NoTee;


use NoTee\Exceptions\PathOutdatedException;

class DefaultNode implements Fertile, Node
{
    public static $validateAttributes = true;
    public static $validateAttributeNames = true;

    protected $tagName;
    protected $attributes;
    /** @var array-of-HtmlNode */
    protected $children;

    protected static $urlAttributes = [
        'action' => true,
        'archive' => true,
        'cite' => true,
        'classid' => true,
        'codebase' => true,
        'data' => true,
        'formaction' => true,
        'href' => true,
        'icon' => true,
        'longdesc' => true,
        'manifest' => true,
        'poster' => true,
        'src' => true,
        'usemap' => true,
    ];

    public function __construct($tagName, array $attributes = [], array $children = [])
    {
        static::validateAttributes($attributes);
        $this->tagName = $tagName;
        $this->attributes = $attributes;
        foreach($children as &$child) {
            if(is_string($child)) {
                $child = new TextNode($child);
            }
        }
        $this->children = $children;
    }

    public function toString()
    {
        $attributeString = !empty($this->attributes) ? ' ' . $this->getAttributeString() : '';
        if(isset($this->children[0])) {
            $result = '';
            /** @var Node $child */
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
            $escapedAttribute = $this->escapeAttribute($name, $value);
            $allAttributes[] = $name . '="' . $escapedAttribute . '"';
        }
        return implode(' ', $allAttributes);
    }

    private function escapeAttribute($name, $value)
    {
        if(array_key_exists($name, static::$urlAttributes)) {
            return $value->toString();
        }
        return htmlspecialchars($value);
    }


    /** VALIDATION */

    private static function validateAttributes($attributes)
    {
        if(static::$validateAttributes) {
            foreach($attributes as $key => $value) {
                static::validateAttribute($key, $value);
            }
        }
    }

    private static function isValidAttributeName($attributeName)
    {
        $regex = '/^[0-9a-z-_]*$/i';
        return preg_match($regex, $attributeName) ? true : false;
    }

    private static function validateAttribute($key, $value)
    {
        if(static::$validateAttributeNames && !static::isValidAttributeName($key)){
            throw new \InvalidArgumentException("invalid attribute name $key");
        }
        if(array_key_exists($key, static::$urlAttributes)) {
            if(!$value instanceof URLAttribute) {
                throw new \InvalidArgumentException("attribute $key has to be instance of URLAttribute");
            }
        }
    }



    /** CHILD OPERATIONS */

    /**
     * @param Node $oldChild
     * @param Node $newChild
     * @return DefaultNode|Node
     */
    public function replaceChild(Node $oldChild, Node $newChild)
    {
        // TODO: Implement deleteChildAt() method.
    }

    public function deleteChildAt(Node $child)
    {
        // TODO: Implement deleteChildAt() method.
    }

    public function insertChildAt($index, Node $child)
    {
        // TODO: Implement insertChildAt() method.
    }


    /**
     * @return array-of-INode
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function _executeOnPath(array $replacementMap, $operation, array $parameter)
    {
        /** @var ReplacementMapStep $replacement */
        $replacement = array_shift($replacementMap);
        if(count($replacementMap) === 0) {
            if($replacement->getOldNode() !== $this) {
                throw new PathOutdatedException();
            }
            return $this->execute($replacement->getNewNode(), $operation, $parameter);
        }

        $clone = $replacement->getNewNode();
        /** @var ReplacementMapStep $nextChild */
        $nextChild = $replacementMap[0];
        $nextIndex = $nextChild->getIndex();
        $nextObject = $nextChild->getOldNode();
        if(!isset($clone->children[$nextIndex])) {
            throw new PathOutdatedException();
        }
        $clone->children[$nextIndex] = $clone->children[$nextIndex]->_executeOnPath($replacementMap, $operation, $parameter);
        $clone->children = array_values($clone->children);
        return $clone;
    }

    protected function execute(Node $newNode, $operation, $parameter)
    {
        call_user_func_array([$newNode, $operation], $parameter);
        return $newNode;
    }


    /** HELPER METHODS */

    /**
     * @param $class
     */
    protected function addClass($class) {
        if(isset($this->attributes['class'])) {
            $allClasses = explode(' ', $this->attributes['class']);
            if(!in_array($class, $allClasses)) {
                $allClasses[] = $class;
            }
            $this->attributes['class'] = trim(implode(' ', $allClasses));
        } else {
            $this->attributes['class'] = trim($class);
        }
    }

    protected function removeClass($class)
    {
        if(!isset($this->attributes['class'])) {
            return;
        }

        $allClass = explode(' ', $this->attributes['class']);
        $this->attributes['class'] = trim(implode(' ', array_filter($allClass, function($existingClass) use ($class) {
            return $existingClass !== $class;
        })));
    }

    /**
     * @return mixed
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function find($query)
    {
        return new Processor($this, Selector::select($this, $query));
    }

}
