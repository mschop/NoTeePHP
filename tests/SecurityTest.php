<?php

declare(strict_types=1);

namespace NoTee;

class SecurityTest extends \PHPUnit_Framework_TestCase
{

    private function getEscaper()
    {
        return new EscaperForNoTeeContext('utf-8');
    }

    public function test_EscapesAttributes()
    {
        $node = new DefaultNode('div', $this->getEscaper(), ['class' => '"classname']);
        $this->assertEquals('<div class="&quot;classname"></div>', (string)$node);
    }

    public function test_InvalidAttributeName_ThrowsException()
    {
        $nf = new NodeFactory('utf-8');
        $this->setExpectedException('InvalidArgumentException');
        $nf->a(['_invalid>attribute<name' => 'google.de']);
    }

    public function test_UriInjection()
    {
        $nf = new NodeFactory('utf-8');
        $this->setExpectedException('InvalidArgumentException');
        $nf->a(
            ['href' => 'javascript: alert(1)']
        );
    }

    public function test_UriInjectionWithWhitespace()
    {
        $nf = new NodeFactory('utf-8');
        $this->setExpectedException('InvalidArgumentException');
        $nf->a(['href' => '  javascript: alert(1)   ']);
    }
}
