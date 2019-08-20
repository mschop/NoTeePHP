<?php

declare(strict_types=1);

namespace NoTee;

use NoTee\Nodes\DocumentNode;
use PHPUnit\Framework\TestCase;

class NodeFactoryTest extends TestCase
{

    /** @var  NodeFactory */
    private $nf;

    /**
     * @before
     */
    public function before()
    {
        $this->nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator());
    }

    public function Test_ComplexStructure()
    {

        $node = $this->nf->div(
            [],
            $this->nf->span(
                ['class' => 'class1 class2'],
                'this library is for writing ',
                $this->nf->abbr(['title' => 'Hypertext Markup Language'], 'html')
            )
        );

        $expected = '<div><span class="class1 class2">this library is for writing <abbr title="Hypertext Markup Language">html</abbr></span></div>';
        $this->assertEquals($expected, $node->__toString());
    }

    public function test()
    {
        $node = $this->nf->div(
            $this->nf->a(
                'hello world'
            )
        );
        $this->assertEquals('<div><a>hello world</a></div>', $node->__toString());


        $node = $this->nf->div(
            [$this->nf->a(), $this->nf->abbr()],
            $this->nf->span()
        );
        $this->assertEquals('<div><a></a><abbr></abbr><span></span></div>', $node->__toString());

        $node = $this->nf->div(
            ['class' => 'hello'],
            [$this->nf->a(), $this->nf->abbr()],
            null,
            $this->nf->span('test')
        );
        $this->assertEquals('<div class="hello"><a></a><abbr></abbr><span>test</span></div>', $node->__toString());

    }

    public function test_debugMode()
    {
        $nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator(), true);
        $root = $nf->span();
        $this->assertEquals('<span data-source="' . __FILE__ . ':' . '69"></span>', (string)$root);
    }

    public function test_debugModeWithExistingAttributes()
    {
        $nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator(), true);
        $root = $nf->span(['id' => 'hello-world']);
        $this->assertEquals('<span id="hello-world" data-source="' . __FILE__ . ':' . '76"></span>', (string)$root);
    }

    public function test_textAndRaw()
    {
        $nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator());
        $node = $nf->text('test>');
        $this->assertEquals('test&gt;', (string)$node);
        $node = $nf->raw('test>');
        $this->assertEquals('test>', (string)$node);
    }

    public function testDocument()
    {
        $nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator());
        $doc = $nf->document(
            'html',
            $nf->html(
                $nf->head(

                ),
                $nf->body(
                    'test'
                )
            )
        );
        $this->assertEquals("<!DOCTYPE html>\n<html><head></head><body>test</body></html>", (string)$doc);
    }

    public function test_issue57_firstItemInFirstParameterIsNull_shouldBeInterpretedAsNode()
    {
        $nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator());
        $node = $nf->div([null, $nf->text('test')]);
        $this->assertEquals('<div>test</div>', (string)$node);
    }

    public function test_childNeitherObjectNorString()
    {
        $nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator());
        $node = $nf->div('1', 1);
        $this->assertEquals($nf->div('1', '1'), $node);
    }

    public function test_wrapper()
    {
        $nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator());
        $wrapper = $nf->wrapper(
            $nf->div('test'),
            [
                $nf->div('test2'),
                'test3'
            ]
        );
        $this->assertEquals('<div>test</div><div>test2</div>test3', (string)$wrapper);
    }
}
