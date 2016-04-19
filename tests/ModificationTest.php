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

    public function test_append()
    {
        $nf = new NodeFactory('utf-8');
        $root = $nf->ul(
            $nf->li(
                $nf->ul(
                    $nf->li('text')
                )
            ),
            $nf->li('text')
        );
        $this->assertEquals('<ul><li><ul><li>text</li></ul></li><li>text</li></ul>', (string)$root);

        $newRoot = $root->find('ul')->append($nf->li('text2'))->getRoot();
        $this->assertEquals('<ul><li><ul><li>text</li><li>text2</li></ul></li><li>text</li><li>text2</li></ul>', (string)$newRoot);
    }

}
