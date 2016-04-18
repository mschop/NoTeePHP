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

    public function test_toggleClass()
    {
        $nf = new NodeFactory('utf-8');
        $root = $nf->div(
            $nf->div(
                ['class' => 'toggle'],
                $nf->div()
            )
        );
        $this->assertEquals('<div><div class="toggle"><div /></div></div>', (string)$root);
        $toggledRoot = $root->find('div')->toggleClass('toggle')->getRoot();
        $this->assertEquals('<div class="toggle"><div><div class="toggle" /></div></div>', (string)$toggledRoot);
    }

}
