<?php

namespace NoTee;

use InvalidArgumentException;
use NoTee\Nodes\DefaultNode;
use NoTee\Nodes\DocumentNode;
use NoTee\Nodes\RawNode;
use NoTee\Nodes\TextNode;
use NoTee\Nodes\WrapperNode;

abstract class AbstractNodeFactory
{
    protected const URI_ATTRIBUTES = [
        'action' => true,
        'archive' => true,
        'cite' => true,
        'classid' => true,
        'codebase' => true,
        'data' => true,
        'formaction' => true,
        'href' => true,
        'icon' => true,
        'longdesc' => true,
        'manifest' => true,
        'poster' => true,
        'src' => true,
        'usemap' => true,
    ];

    protected EscaperInterface $escaper;
    protected UriValidatorInterface $uriValidator;
    protected bool $debug;
    /** @var SubscriberInterface[] */
    protected array $subscriber = [];

    public function __construct(
        EscaperInterface $escaper,
        UriValidatorInterface $uriValidator,
        bool $debug = false
    )
    {
        $this->escaper = $escaper;
        $this->uriValidator = $uriValidator;
        $this->debug = $debug;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return DefaultNode
     * @throws InvalidArgumentException
     */
    public function create(string $name, array $arguments): DefaultNode
    {
        $attributes = [];
        if ($this->debug) $attributes['data-source'] = $this->generateDebugSource();

        $firstArgument = reset($arguments);
        if (is_array($firstArgument)) {
            $firstAttribute = reset($firstArgument);
            if ($firstAttribute !== null && !$firstAttribute instanceof NodeInterface) {
                $attributes = array_merge($firstArgument, $attributes);
                $this->validateAttributes($attributes);
                // remove the first element (because it contains the attributes)
                array_shift($arguments);
            }
        }

        return $this->notify(
            new DefaultNode(
                $name,
                $this->escaper,
                $attributes,
                static::flatten($arguments)
            )
        );
    }

    /**
     * Get information on where a node has been created
     * @return string
     */
    protected function generateDebugSource()
    {
        $trace = debug_backtrace();
        $callee = $trace[2];
        return $callee['file'] . ':' . $callee['line'];
    }

    /**
     * @param array $attributes
     * @throws InvalidArgumentException
     */
    protected function validateAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (!$this->isValidAttributeKey($key)) {
                throw new \InvalidArgumentException('invalid attribute name ' . $key);
            }
            if (!$this->isValidAttributeValue($key, $value)) {
                throw new \InvalidArgumentException('invalid attribute value for ' . $key);
            }
        }
    }

    protected function isValidAttributeKey(string $key): bool
    {
        if (!preg_match('/^[0-9a-z-_]*$/i', $key)) {
            return false;
        }
        return true;
    }

    protected function isValidAttributeValue(string $key, string $value): bool
    {
        if (array_key_exists($key, static::URI_ATTRIBUTES)) {
            return $this->uriValidator->isValid($value);
        }
        return true;
    }

    /**
     * An api consumer can pass arrays coming from function calls as children to the method "create". Elements in this
     * array are direct children of the node created with the method "create". Those must therefore be flattened.
     * @param array $arguments
     * @return array
     */
    protected static function flatten(array $arguments): array
    {
        $result = [];
        foreach ($arguments as $argument) {
            if (is_array($argument)) {
                $result = array_merge($result, $argument);
            } elseif ($argument !== null) {
                $result[] = $argument;
            }
        }
        return $result;
    }

    /**
     * Notify all subscriber
     *
     * @param DefaultNode $node
     * @return DefaultNode
     */
    protected function notify(DefaultNode $node): DefaultNode
    {
        foreach ($this->subscriber as $subscriber) {
            $node = $subscriber->notify($this, $node);
        }

        return $node;
    }

    /**
     * Add an event listener. The event listener is called when a new DefaultNode is created.
     *
     * @param SubscriberInterface $callable
     */
    public function subscribe(SubscriberInterface $callable)
    {
        $this->subscriber[] = $callable;
    }

    /**
     * Output escaped text.
     *
     * @param string $text
     * @return TextNode
     */
    public function text(string $text): TextNode
    {
        return new TextNode($text, $this->escaper);
    }

    /**
     * This method creates a RawNode instance. RawNode is used to output unescaped html content.
     *
     * @param string $text
     * @return RawNode
     */
    public function raw(string $text): RawNode
    {
        return new RawNode($text);
    }

    /**
     * The Document-Node is used for creating the DOCTYPE. Even if the html representation of the doctype does not
     * contain any nodes, NodeFactory expects the Document-Node to contain all other nodes.
     *
     * @param string $doctype
     * @param DefaultNode $html
     * @return DocumentNode
     */
    public function document(string $doctype, DefaultNode $html): DocumentNode
    {
        return new DocumentNode($doctype, $html);
    }

    /**
     * Creates a wrapper, that does not produce any HTML. This method is useful for cases, where you need to output
     * multiple nodes.
     *
     * Example:
     *
     * $values = [1, 2, 3];
     * $nodeFactory->ul(
     *   $nodeFactory->wrapper(array_map(function ($value) { return $nodeFactory->li($value); })
     * )
     *
     * @return WrapperNode
     */
    public function wrapper(): WrapperNode
    {
        return new WrapperNode(static::flatten(func_get_args()), $this->escaper);
    }

    /**
     * This method handles all default node types.
     *
     * @param $name
     * @param $arguments
     * @return NodeInterface
     */
    public function __call($name, $arguments): NodeInterface
    {
        return $this->create($name, $arguments);
    }

    /**
     * @return EscaperInterface
     */
    public function getEscaper(): EscaperInterface
    {
        return $this->escaper;
    }
}
