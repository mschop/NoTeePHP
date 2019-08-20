<?php

declare(strict_types=1);

namespace NoTee;

use InvalidArgumentException;
use NoTee\Nodes\DefaultNode;
use PHPUnit\Framework\TestCase;

class SecurityTest extends TestCase
{

    private function getEscaper()
    {
        return new DefaultEscaper('utf-8');
    }

    public function test_EscapesAttributes()
    {
        $node = new DefaultNode('div', $this->getEscaper(), ['class' => '"classname']);
        $this->assertEquals('<div class="&quot;classname"></div>', (string)$node);
    }

    public function test_InvalidAttributeName_ThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator());
        $nf->a(['_invalid>attribute<name' => 'google.de']);
    }

    public function test_UriInjection()
    {
        $nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator());
        $this->expectException(InvalidArgumentException::class);
        $nf->a(
            ['href' => 'javascript: alert(1)']
        );
    }

    public function test_UriInjectionWithWhitespace()
    {
        $nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator());
        $this->expectException(InvalidArgumentException::class);
        $nf->a(['href' => '  javascript: alert(1)   ']);
    }
}
