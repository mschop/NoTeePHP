<?php

namespace NoTee;

use NoTee\NodeFactory as N;

class ProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function test_addClass_doneRight_correctResult()
    {
        $root1 = _div(
            ['class' => 'a b c'],
            _div(
                ['class' => 'class1 class2']
            ),
            _div(['class' => 'classA classB'])
        );

        $processor = new Processor($root1, [
            [new PathStep(null, $root1)]
        ]);
        /** @var DefaultNode $root2 */
        $root2 = $processor->addClass('d')->getRoot();
        $this->assertEquals('<div class="a b c"><div class="class1 class2" /><div class="classA classB" /></div>', $root1->toString());
        $this->assertEquals('<div class="a b c d"><div class="class1 class2" /><div class="classA classB" /></div>', $root2->toString());
        $root3 = $processor->addClass('SomeClass')->getRoot();
        $this->assertEquals('<div class="a b c d SomeClass"><div class="class1 class2" /><div class="classA classB" /></div>', $root3->toString());


        $processor = new Processor($root1, [
            [new PathStep(null, $root1), new PathStep(0, $root1->getChildren()[0])]
        ]);
        $root4 = $processor->addClass('class3')->addClass('class4')->getRoot();
        $this->assertEquals('<div class="a b c"><div class="class1 class2 class3 class4" /><div class="classA classB" /></div>', $root4->toString());
    }

    public function test_addClass_onTextNode_throwsException()
    {
        $text = _text('mytext');
        $root = _div(
            [],
            $text
        );

        $processor = new Processor($root, [
            [new PathStep(null, $root), new PathStep(0, $text)]
        ]);
        $this->setExpectedException('NoTee\Exceptions\InvalidOperationException');
        $processor->addClass('test');
    }

    public function test_addClass_onRawNode_throwsException()
    {
        $raw = _raw('mytext');
        $root = _div(
            [],
            $raw
        );

        $processor = new Processor($root, [
            [new PathStep(0, $root), new PathStep(0, $raw)]
        ]);
        $this->setExpectedException('NoTee\Exceptions\InvalidOperationException');
        $processor->addClass('test');
    }

    public function test_setRaw_wrongNode_throwsException()
    {
        $raw = _raw('mytext');
        $wrong = _raw('other text');
        $root = _div(
            [],
            $raw
        );

        $processor = new Processor($root, [
            [new PathStep(0, $root), new PathStep(0, $wrong)]
        ]);
        $this->setExpectedException('NoTee\Exceptions\PathOutdatedException');
        $processor->setRaw('test');
    }

    public function test_setText_wrongNode_throwsException()
    {
        $text = _text('mytext');
        $wrong = _text('other text');
        $root = _div(
            [],
            $text
        );

        $processor = new Processor($root, [
            [new PathStep(0, $root), new PathStep(0, $wrong)]
        ]);
        $this->setExpectedException('NoTee\Exceptions\PathOutdatedException');
        $processor->setText('test');
    }

    public function test_addClass_wrongObject_throwsException()
    {
        $rightObject = _div([]);
        $wrongObject = _div([]);
        $root = _div([], $rightObject);
        $processor = new Processor($root, [
            [new PathStep(null, $root), new PathStep(0, $wrongObject)]
        ]);
        $this->setExpectedException('NoTee\Exceptions\PathOutdatedException');
        $processor->addClass('class');
    }

    public function test_addClass_nested()
    {
        $root = _div(
            [],
            _div(
                [],
                _div([])
            )
        );

        $processor = new Processor($root, [
            [new PathStep(null, $root), new PathStep(0, $root->getChildren()[0])],
            [
                new PathStep(null, $root),
                new PathStep(0, $root->getChildren()[0]),
                new PathStep(0, $root->getChildren()[0]->getChildren()[0])
            ],
        ]);

        $newRoot = $processor->addClass('test')->getRoot();
        $this->assertEquals('<div><div class="test"><div class="test" /></div></div>', $newRoot->toString());
    }

}
