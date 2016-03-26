<?php

namespace NoTee;


class ReplacementMapStep
{

    private $index;
    private $oldNode;
    private $newNode;

    /**
     * PathStep constructor.
     * @param $index
     * @param $newNode
     */
    public function __construct($index, $oldNode, $newNode)
    {
        $this->index = $index;
        $this->oldNode = $oldNode;
        $this->newNode = $newNode;
    }

    /**
     * @return integer
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return Node
     */
    public function getOldNode()
    {
        return $this->oldNode;
    }

    /**
     * @return Node
     */
    public function getNewNode()
    {
        return $this->newNode;
    }



}
