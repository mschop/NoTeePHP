<?php

namespace NoTee;


use NoTee\Exceptions\InvalidOperationException;
use NoTee\Exceptions\PathOutdatedException;

class Raw implements URLAttribute, Node
{

    private $raw;

    public function __construct($value)
    {
        $this->raw = $value;
    }

    public function toString()
    {
        return $this->raw;
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

    protected function setRaw($raw)
    {
        $this->raw = $raw;
    }

}
