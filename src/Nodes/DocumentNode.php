<?php

declare(strict_types=1);

namespace NoTee\Nodes;


use NoTee\NodeInterface;

class DocumentNode implements NodeInterface
{
    protected string $doctype;
    protected NodeInterface $topNode;

    public function __construct(string $doctype, NodeInterface $topNode)
    {
        $this->doctype = $doctype;
        $this->topNode = $topNode;
    }

    public function __toString(): string
    {

        return '<!DOCTYPE ' . $this->doctype . ">\n" . (string)$this->topNode;
    }

}
