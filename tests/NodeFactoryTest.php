<?php

namespace NoTee;

use NoTee\NodeFactory as N;

class NodeFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function test_complexStructure()
    {
        $node = N::div(
            [],
            N::span(
                ['class' => 'class1 class2'],
                N::text('this library is for writing '),
                N::abbr(['title' => 'Hypertext Markup Language'], N::text('html'))
            )
        );

        $expected = '<div><span class="class1 class2">this library is for writing <abbr title="Hypertext Markup Language">html</abbr></span></div>';
        $this->assertEquals($expected, $node->toString());
    }
}
