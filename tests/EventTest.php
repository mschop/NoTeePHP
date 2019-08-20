<?php

declare(strict_types=1);

namespace NoTee;


use NoTee\Nodes\DefaultNode;
use PHPUnit\Framework\TestCase;

class EventTestFormTagSubscriber implements SubscriberInterface
{
    public function notify(NodeFactory $nodeFactory, DefaultNode $node): DefaultNode
    {
        if ($node->getTagName() !== 'form') return $node;
        $children = array_merge(
            [$nodeFactory->input(['type' => 'hidden', 'value' => '12345', 'name' => 'xsrf_token'])],
            $node->getChildren()
        );
        return new DefaultNode('form', $node->getEscaper(), $node->getAttributes(), $children);
    }
}

class EventTest extends TestCase
{
    public function test()
    {
        $nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator());

        $expected = $nf->form(
            ['class' => 'someclass', 'id' => 'someid'],
            $nf->input(['type' => 'hidden', 'value' => '12345', 'name' => 'xsrf_token']),
        );

        $nf->subscribe(new EventTestFormTagSubscriber());

        $node = $nf->form(
            ['class' => 'someclass', 'id' => 'someid']
        );

        $this->assertEquals((string)$expected, (string)$node);

    }
}
