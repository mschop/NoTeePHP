<?php

namespace NoTee;

class DoubleEncodingTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $nf = new NodeFactory('utf-8');
        $nodes = $nf->p(['data-text' => 'M&ouml;ge'], $nf->text('M&ouml;ge'));
        $this->assertEquals('<p data-text="M&ouml;ge">M&ouml;ge</p>', (string)$nodes);
    }
}