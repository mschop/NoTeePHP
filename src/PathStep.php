<?php

namespace NoTee;


class PathStep
{

    public $index;
    public $node;

    /**
     * PathStep constructor.
     * @param $index
     * @param $node
     */
    public function __construct($index, Node $node)
    {
        $this->index = $index;
        $this->node = $node;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return mixed
     */
    public function getNode()
    {
        return $this->node;
    }

}
