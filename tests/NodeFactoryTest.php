<?php

namespace NoTee;

class NodeFactoryTest extends \PHPUnit_Framework_TestCase
{

    /** @var  NodeFactory */
    private $nf;

    /**
     * @before
     */
    public function before()
    {
        $this->nf = new NodeFactory('utf-8', new AttributeValidator(true, true));
    }

    public function test_complexStructure()
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
        $this->assertEquals('<div><a /><abbr /><span /></div>', $node->__toString());

        $node = $this->nf->div(
            ['class' => 'hello'],
            [$this->nf->a(), $this->nf->abbr()],
            null,
            $this->nf->span('test')
        );
        $this->assertEquals('<div class="hello"><a /><abbr /><span>test</span></div>', $node->__toString());

    }

    public function test_construct_hrefAsString_throwsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->nf->a(['href' => 'http://some.url.de']);
    }

    public function test_invalidAttributeValue_throwsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->nf->a(['href' => 'invalid url']);
    }

    public function test_invalidAttributeName_throwsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->nf->a(['_invalid>attribute<name' => new URL('google.de', [])]);
    }

    public function test_invalidEncoding_throwsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        new NodeFactory('utf--8', new AttributeValidator(true, true));
    }

    public function test_debugMode()
    {
        $nf = new NodeFactory('utf-8', new AttributeValidator(true, true), true);
        $root = $nf->span();
        $this->assertEquals('<span data-source="' . __FILE__ . ':' . '88" />', (string)$root);
    }

    public function test_textAndRaw()
    {
        $nf = new NodeFactory('utf-8', new AttributeValidator(true, true), true);
        $node = $nf->text('test>');
        $this->assertEquals('test&gt;', (string)$node);
        $node = $nf->raw('test>');
        $this->assertEquals('test>', (string)$node);
    }

}
