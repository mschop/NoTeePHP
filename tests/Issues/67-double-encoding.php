<?php

namespace NoTee;

use PHPUnit\Framework\TestCase;

class DoubleEncodingTest extends TestCase
{
    public function test()
    {
        $nf = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator());
        $nodes = $nf->p(['data-text' => 'M&ouml;ge'], $nf->text('M&ouml;ge'));
        $this->assertEquals('<p data-text="M&ouml;ge">M&ouml;ge</p>', (string)$nodes);
    }
}