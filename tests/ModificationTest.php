<?php

namespace NoTee;

class ModificationTest extends \PHPUnit_Framework_TestCase
{

    public function test_removeClass()
    {
        $h = new NodeFactory('utf-8');
        $root = $h->div(['class' => ' a b c ']);
        $modified = $root->find('div')->removeClass('b')->getRoot();
        $this->assertEquals('<div class="a c" />', $modified->__toString());
    }

}
