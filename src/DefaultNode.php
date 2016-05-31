<?php

namespace NoTee;


use NoTee\Exceptions\PathOutdatedException;

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
            return $value->toString();
        }
        return $this->escaper->escapeAttribute($value);
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




    /*
     * JQUERY LIKE METHODS
     */

    /**
     * @param $class
     */
    protected function addClass($class) {
        $class = $this->escapeAttribute($class);
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
        if(isset($this->attributes['class'])) {
            $allClass = explode(' ', $this->attributes['class']);
            $reducedClass = array_filter($allClass, function($existingClass) use ($class) {
                return $existingClass !== $class;
            });
            if(count($reducedClass) === 0) {
                unset($this->attributes['class']);
            } else {
                $this->attributes['class'] = trim(implode(' ', $reducedClass));
            }
        }
    }

    protected function toggleClass($class)
    {
        $cleanedClass = trim($this->escapeAttribute($class));
        if(isset($this->attributes['class']) && in_array($cleanedClass, explode(' ', $this->attributes['class']))) {
            $this->removeClass($class);
        } else {
            $this->addClass($class);
        }
    }

    protected function append(Node $node)
    {
        $this->children[] = $node;
    }

    protected function setAttr($name, $value)
    {

        $this->attributes[$name] = $value;
    }

    protected function insertAfter(ReplacementMapStep $mapStep, Node $newChild)
    {
        $newChildArray = [];
        foreach($this->children as $child) {
            $newChildArray[] = $child;
            if($child === $mapStep->getOldNode()) {
                $newChildArray[] = $newChild;
            }
        }
        $this->children = $newChildArray;
    }

    protected function insertBefore(ReplacementMapStep $mapStep, Node $newChild)
    {
        $newChildArray = [];
        foreach($this->children as $child) {
            if($child === $mapStep->getOldNode()) {
                $newChildArray[] = $newChild;
            }
            $newChildArray[] = $child;
        }
        $this->children = $newChildArray;
    }

    protected function insertChildAt($insertAt, Node $newChild)
    {
        $newChildArray = [];
        foreach($this->children as $index => $child) {
            if($index === $insertAt) {
                $newChildArray[] = $newChild;
            }
            $newChildArray[] = $child;
        }
        $this->children = $newChildArray;
    }

    protected function prepend(Node $newChild)
    {
        array_unshift($this->children, $newChild);
    }

    protected function remove(ReplacementMapStep $mapStep)
    {
        $newChildArray = [];
        $removedChild = $mapStep->getOldNode();
        foreach($this->children as $child) {
            if($child !== $removedChild) {
                $newChildArray[] = $child;
            }
        }
        $this->children = $newChildArray;
    }

}
