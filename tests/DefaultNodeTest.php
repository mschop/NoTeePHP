<?php

namespace NoTee;


class DefaultNodeTest extends \PHPUnit_Framework_TestCase
{
    private function getEscaper()
    {
        return new EscaperForNoTeeContext('utf-8');
    }

    public function test_toString_emptyNode_selfClosingTag()
    {
        $node = new DefaultNode('p', $this->getEscaper(), [], []);
        $this->assertEquals('<p />', (string)$node);
    }

    public function test_toString_nestedNodes_correctResult()
    {
        $node1 = new DefaultNode('p', $this->getEscaper(), [], []);
        $node2 = new DefaultNode('p', $this->getEscaper(), [], [$node1]);
        $this->assertEquals('<p><p /></p>', (string)$node2);
    }

    public function test_toString_usingAttributes_correctAttributeString()
    {
        $node1 = new DefaultNode('p', $this->getEscaper(), ['id' => 'testid', 'class' => 'child'], []);
        $node2 = new DefaultNode('p', $this->getEscaper(), ["id" => "testid3"], []);
        $parentNode = new DefaultNode('p', $this->getEscaper(), ["id" => "testid2", "class" => "parent"], [$node1, $node2]);
        $expectedResult = '<p id="testid2" class="parent"><p id="testid" class="child" /><p id="testid3" /></p>';
        $this->assertEquals($expectedResult, (string)$parentNode);
    }

    public function test_toString_shouldEscapeDoubleQuotesInAttributes()
    {
        $node = new DefaultNode('p', $this->getEscaper(), ['id' => '"id'], []);
        $this->assertEquals('<p id="&quot;id" />', (string)$node);
    }

    public function test_toString_shouldEscapeURLComponents()
    {
        $parameter = [
            'searchRequest' => '"'
        ];
        $node = new DefaultNode('a', $this->getEscaper(), ['href' => new URLAttributeValue('http://www.some-domain.com', $parameter)]);
        $this->assertEquals('<a href="http://www.some-domain.com?searchRequest=%22" />', (string)$node);
    }

    public function test_toString_shouldNotEscapeRawUrl()
    {
        $raw = new RawAttributeValue('"#');
        $node = new DefaultNode('a', $this->getEscaper(), ['href' => $raw]);
        $this->assertEquals('<a href=""#" />', (string)$node);
    }

    public function test_escapesAttributes()
    {
        $node = new DefaultNode('div', $this->getEscaper(), ['class' => '"classname']);
        $this->assertEquals('<div class="&quot;classname" />', (string)$node);
    }

    public function test_scriptTagNotSelfClosing()
    {
        $node = new DefaultNode('script', $this->getEscaper(), ['src' => new RawAttributeValue('file.js')]);
        $this->assertEquals('<script src="file.js"></script>', (string)$node);
    }

}
