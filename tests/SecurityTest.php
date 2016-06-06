<?php

namespace NoTee;


class SecurityTest extends \PHPUnit_Framework_TestCase
{
    public function test_href_injection()
    {
        $nf = new NodeFactory('utf-8');
        $this->setExpectedException('InvalidArgumentException');
        $nf->a(
            ['href' => new URLAttributeValue('javascript: alert(1)', [])]
        );
    }

    public function test_href_injection_whitespace()
    {
        $nf = new NodeFactory('utf-8');
        $this->setExpectedException('InvalidArgumentException');
        $nf->a(['href' => new URLAttributeValue('  javascript: alert(1)   ', [])]);
    }
}
