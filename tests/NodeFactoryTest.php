<?php

namespace NoTee;

use NoTee\NodeFactory as N;
require_once(__DIR__ . '/../globalfunctions.php');

class NodeFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function test_complexStructure()
    {
        $node = _div(
            [],
            _span(
                ['class' => 'class1 class2'],
                _text('this library is for writing '),
                _abbr(['title' => 'Hypertext Markup Language'], _text('html'))
            )
        );

        $expected = '<div><span class="class1 class2">this library is for writing <abbr title="Hypertext Markup Language">html</abbr></span></div>';
        $this->assertEquals($expected, $node->toString());
    }

    public function test()
    {
        $node = _div(
            _a(
                _text('hello world')
            )
        );
        $this->assertEquals('<div><a>hello world</a></div>', $node->toString());


        $node = _div(
            [_a(), _abbr()],
            _span()
        );
        $this->assertEquals('<div><a /><abbr /><span /></div>', $node->toString());

        $node = _div(
            ['class' => 'hello'],
            [_a(), _abbr()],
            null,
            _span('test')
        );
        $this->assertEquals('<div class="hello"><a /><abbr /><span>test</span></div>', $node->toString());

    }
}
