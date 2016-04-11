<?php

namespace NoTee;

class NodeFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function test_complexStructure()
    {
        $h = new NodeFactory();
        $node = $h->div(
            [],
            $h->span(
                ['class' => 'class1 class2'],
                'this library is for writing ',
                $h->abbr(['title' => 'Hypertext Markup Language'], 'html')
            )
        );

        $expected = '<div><span class="class1 class2">this library is for writing <abbr title="Hypertext Markup Language">html</abbr></span></div>';
        $this->assertEquals($expected, $node->__toString());
    }

    public function test()
    {
        $h = new NodeFactory();
        $node = $h->div(
            $h->a(
                'hello world'
            )
        );
        $this->assertEquals('<div><a>hello world</a></div>', $node->__toString());


        $node = $h->div(
            [$h->a(), $h->abbr()],
            $h->span()
        );
        $this->assertEquals('<div><a /><abbr /><span /></div>', $node->__toString());

        $node = $h->div(
            ['class' => 'hello'],
            [$h->a(), $h->abbr()],
            null,
            $h->span('test')
        );
        $this->assertEquals('<div class="hello"><a /><abbr /><span>test</span></div>', $node->__toString());

    }

    public function test_construct_hrefAsString_throwsException()
    {
        $nf = new NodeFactory();
        $this->setExpectedException('InvalidArgumentException');
        $nf->a(['href' => 'http://some.url.de']);
    }

    public function test_construct_invalidAttributeName_throwsException()
    {
        $nf = new NodeFactory();
        $this->setExpectedException('InvalidArgumentException');
        $nf->a(['href' => ['a b' => 'c']]);
    }

}
