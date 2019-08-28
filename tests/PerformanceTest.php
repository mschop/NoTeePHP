<?php


namespace NoTee;


use NoTee\Nodes\DefaultNode;
use PHPUnit\Framework\TestCase;

class SubscriberDoingNothing implements SubscriberInterface
{
    public function notify(NodeFactory $nodeFactory, DefaultNode $node): DefaultNode
    {
        return $node;
    }
}

class PerformanceTest extends TestCase
{
    public function test()
    {
        $nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator());
        $nf->subscribe(new SubscriberDoingNothing());
        $start = microtime(true);
        $items = [];
        for($x = 0; $x < 1000; $x++) {
            $items[] = $nf->div(['class' => 'hello-world']);
        }
        $node = $nf->div($items);
        (string)$node;
        $end = microtime(true);
        $this->assertLessThan(0.030, $start - $end);
    }
}