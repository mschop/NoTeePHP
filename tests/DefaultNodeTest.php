<?php

namespace NoTee;


class DefaultNodeTest extends \PHPUnit_Framework_TestCase
{
    public function test_toString_emptyNode_selfClosingTag()
    {
        $node = new DefaultNode('p', [], []);
        $this->assertEquals('<p />', $node->toString());
    }

    public function test_toString_nestedNodes_correctResult()
    {
        $node1 = new DefaultNode('p', [], []);
        $node2 = new DefaultNode('p', [], [$node1]);
        $this->assertEquals('<p><p /></p>', $node2->toString());
    }

    public function test_toString_usingAttributes_correctAttributeString()
    {
        $node1 = new DefaultNode('p', ['id' => 'testid', 'class' => 'child'], []);
        $node2 = new DefaultNode('p', ["id" => "testid3"], []);
        $parentNode = new DefaultNode('p', ["id" => "testid2", "class" => "parent"], [$node1, $node2]);
        $expectedResult = '<p id="testid2" class="parent"><p id="testid" class="child" /><p id="testid3" /></p>';
        $this->assertEquals($expectedResult, $parentNode->toString());
    }

    public function test_toString_shouldEscapeDoubleQuotesInAttributes()
    {
        $node = new DefaultNode('p', ['id' => '"id'], []);
        $this->assertEquals('<p id="&quot;id" />', $node->toString());
    }

    public function test_construct_hrefAsString_throwsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $node = new DefaultNode('a', ['href' => 'http://some.url.de']);
    }

    public function test_toString_shouldEscapeURLComponents()
    {
        $parameter = [
            'searchRequest' => '"'
        ];
        $node = new DefaultNode('a', ['href' => new URL('http://www.some-domain.com', $parameter)]);
        $this->assertEquals('<a href="http://www.some-domain.com?searchRequest=%22" />', $node->toString());
    }

    public function test_toString_shouldNotEscapeRawUrl()
    {
        $raw = new RawAttribute('"#');
        $node = new DefaultNode('a', ['href' => $raw]);
        $this->assertEquals('<a href=""#" />', $node->toString());
    }

    public function test_construct_invalidAttributeName_throwsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $node = new DefaultNode('a', ['a b' => 'c']);
    }

    public function test_escapesAttributes()
    {
        $node = new DefaultNode('div', ['class' => '"classname']);
        $this->assertEquals('<div class="&quot;classname" />', $node->toString());
    }

}
