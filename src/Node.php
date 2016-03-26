<?php

namespace NoTee;


interface Node
{
    /**
     * @return string
     */
    public function toString();

    /**
     * @param array-of-ReplacementMapStep $path
     * @param array-of-Node $oldNode
     * @param string $operation
     * @param array $parameter
     * @return mixed
     */
    public function _executeOnPath(array $replacementMap, $operation, array $parameter);

}
