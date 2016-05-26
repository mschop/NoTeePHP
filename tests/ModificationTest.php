<?php

namespace NoTee;

class ModificationTest extends \PHPUnit_Framework_TestCase
{

    private $nf;

    /**
     * @before
     */
    public function before()
    {
        $this->nf = new NodeFactory('utf-8', new AttributeValidator(true, true));
    }

    public function test_removeClass()
    {
        $root = $this->nf->div(['class' => ' a b c ']);
        $modified = $root->find('div')->removeClass('b')->getRoot();
        $this->assertEquals('<div class="a c" />', $modified->__toString());
    }

    public function test_toggleClass()
    {
        $root = $this->nf->div(
            $this->nf->div(
                ['class' => 'toggle'],
                $this->nf->div()
            )
        );
        $this->assertEquals('<div><div class="toggle"><div /></div></div>', (string)$root);
        $toggledRoot = $root->find('div')->toggleClass('toggle')->getRoot();
        $this->assertEquals('<div class="toggle"><div><div class="toggle" /></div></div>', (string)$toggledRoot);
    }

    public function test_append()
    {
        $root = $this->nf->ul(
            $this->nf->li(
                $this->nf->ul(
                    $this->nf->li('text')
                )
            ),
            $this->nf->li('text')
        );
        $this->assertEquals('<ul><li><ul><li>text</li></ul></li><li>text</li></ul>', (string)$root);

        $newRoot = $root->find('ul')->append($this->nf->li('text2'))->getRoot();
        $this->assertEquals('<ul><li><ul><li>text</li><li>text2</li></ul></li><li>text</li><li>text2</li></ul>', (string)$newRoot);
    }

    public function test_getAttr()
    {
        $root = $this->nf->div(
            $this->nf->span(['class' => 'item', 'title' => 'some title']),
            $this->nf->div(
                $this->nf->span(['title' => 'other title'])
            )
        );
        $this->assertEquals('<div><span class="item" title="some title" /><div><span title="other title" /></div></div>', (string)$root);
        $this->assertEquals('some title', $root->find('span')->getAttr('title'));
    }

    public function test_setAttr()
    {
        $root = $this->nf->div(
            $this->nf->span(['class' => 'item', 'title' => 'some title']),
            $this->nf->div(
                $this->nf->span(['title' => 'other title'])
            )
        );
        $this->assertEquals('<div><span class="item" title="some title" /><div><span title="other title" /></div></div>', (string)$root);
        $newRoot = $root->find('span')->setAttr('title', 'next title')->getRoot();
        $this->assertEquals('<div><span class="item" title="next title" /><div><span title="next title" /></div></div>', (string)$newRoot);
    }

}
