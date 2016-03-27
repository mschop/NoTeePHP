<?php

namespace NoTee;


interface Fertile
{

    /**
     * @param Node $oldChild
     * @param Node $newChild
     * @return Node
     */
    public function replaceChild(Node $oldChild, Node $newChild);

    /**
     * @param Node $child
     * @return Node
     */
    public function deleteChildAt(Node $child);

    /**
     * @param $index
     * @param Node $child
     * @return Node
     */
    public function insertChildAt($index, Node $child);

}
