<?php


namespace NoTee;


use PHPUnit\Framework\TestCase;

class GlobalTest extends TestCase
{
    public function test()
    {
        global $noTeePHP;
        $noTeePHP = new NodeFactory(new DefaultEscaper('utf-8'), new UriValidator());
        require __DIR__ . '/../global.php';
        $this->assertEquals('<a></a>', _a());
    }
}