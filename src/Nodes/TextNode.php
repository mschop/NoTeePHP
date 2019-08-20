<?php

declare(strict_types=1);

namespace NoTee\Nodes;

use NoTee\EscaperInterface;
use NoTee\NodeInterface;

class TextNode implements NodeInterface
{
    protected string $text;

    public function __construct(string $text, EscaperInterface $escaper)
    {
        $this->text = $escaper->escapeHtml($text);
    }

    public function __toString(): string
    {
        return $this->text;
    }

}
