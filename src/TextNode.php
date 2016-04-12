<?php

namespace NoTee;


use NoTee\Exceptions\InvalidOperationException;
use NoTee\Exceptions\PathOutdatedException;

class TextNode implements Node
{

    private $text;

    public function __construct($text, Escaper $escaper)
    {
        $this->text = $escaper->escapeHtml($text);
    }

    public function __toString()
    {
        return $this->text;
    }

    public function _executeOnPath(array $replacementMap, $operation, array $parameter)
    {
        /** @var ReplacementMapStep $replacement */
        $replacement = array_shift($replacementMap);
        $newNode = $replacement->getNewNode();
        if(count($replacementMap) !== 0 || $replacement->getOldNode() !== $this) {
            throw new PathOutdatedException();
        }
        if(!method_exists($newNode, $operation)) {
            throw new InvalidOperationException();
        }
        call_user_func_array([$newNode, $operation], $parameter);
        return $newNode;
    }

    protected function setText($text)
    {
        $this->text = $text;
    }

}
