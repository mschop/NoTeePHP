<?php

namespace NoTee;

require_once(__DIR__ . '/../globalfunctions.php');

class ModificationTest extends \PHPUnit_Framework_TestCase
{

    public function test_removeClass()
    {
        $root = _div(['class' => ' a b c ']);
        $modified = $root->find('div')->removeClass('b')->getRoot();
        $this->assertEquals('<div class="a c" />', $modified->__toString());
    }

}
