<?php

declare(strict_types=1);

namespace NoTee\Nodes;

use NoTee\NodeInterface;

class RawNode implements NodeInterface
{
    protected string $raw;

    public function __construct(string $raw)
    {
        $this->raw = $raw;
    }

    public function __toString(): string
    {
        return $this->raw;
    }
}
