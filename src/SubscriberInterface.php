<?php


namespace NoTee;


use NoTee\Nodes\DefaultNode;

interface SubscriberInterface
{
    public function notify(NodeFactory $nodeFactory, DefaultNode $node): DefaultNode;
}