<?php

declare(strict_types=1);

namespace NoTee\Nodes;


use NoTee\EscaperInterface;
use NoTee\NodeInterface;

class DefaultNode implements NodeInterface
{
    /*
     * VOID_TAGS are those tags, that per html specs are allowed to be have no closing tag but close directly.
     *
     * e.g. <br />
     */
    private const VOID_TAGS = [
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input',
        'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'
    ];

    protected string $tagName;
    protected EscaperInterface $escaper;
    protected array $attributes;

    /** @var array Node[] */
    protected array $children;

    public function __construct(string $tagName, EscaperInterface $escaper, array $attributes = [], array $children = [])
    {
        $this->tagName = $tagName;
        $this->escaper = $escaper;
        $this->attributes = $attributes;
        foreach ($children as &$child) {
            if (!is_object($child)) {
                $child = new TextNode((string)$child, $this->escaper);
            }
        }
        $this->children = $children;
    }

    public function __toString(): string
    {
        $attributeString = !empty($this->attributes) ? ' ' . $this->getAttributeString() : '';
        if (isset($this->children[0]) || !in_array($this->tagName, static::VOID_TAGS)) {
            $result = '';
            foreach ($this->children as $child) {
                assert($child instanceof NodeInterface, 'All children must be instances of Node');
                $result .= $child;
            }
            return '<' . $this->tagName . $attributeString . '>' . $result . '</' . $this->tagName . '>';
        }
        return '<' . $this->tagName . $attributeString . ' />';
    }

    public function getAttributeString(): string
    {
        $attributeString = '';
        $first = true;
        foreach ($this->attributes as $name => $value) {
            $escapedAttribute = $this->escapeAttribute($value);
            $attributeString .= ($first ? '' : ' ') . $name . '="' . $escapedAttribute . '"';
            $first = false;
        }
        return $attributeString;
    }

    /**
     * @return string
     */
    public function getTagName(): string
    {
        return $this->tagName;
    }

    /**
     * @return EscaperInterface
     */
    public function getEscaper(): EscaperInterface
    {
        return $this->escaper;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return NodeInterface[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    protected function escapeAttribute(string $value): string
    {
        return $this->escaper->escapeAttribute($value);
    }
}
