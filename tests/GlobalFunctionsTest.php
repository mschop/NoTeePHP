<?php

namespace NoTee;

require_once(__DIR__ . '/../globalfunctions.php');

class GlobalFunctionsTest extends \PHPUnit_Framework_TestCase
{
    public function test_something()
    {
        $node = _div(
            _a(['href' => _rawAttr('http://google.de')], _text('google.de'))
        );
        $this->assertEquals('<div><a href="http://google.de">google.de</a></div>', $node->__toString());
    }
}
