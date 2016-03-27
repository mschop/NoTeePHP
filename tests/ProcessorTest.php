<?php

namespace NoTee;

use NoTee\NodeFactory as N;

class ProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function test_addClass_doneRight_correctResult()
    {
        $root1 = N::div(
            ['class' => 'a b c'],
            N::div(
                ['class' => 'class1 class2']
            ),
            N::div(['class' => 'classA classB'])
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
        $text = N::text('mytext');
        $root = N::div(
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
        $raw = N::raw('mytext');
        $root = N::div(
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
        $raw = N::raw('mytext');
        $wrong = N::raw('other text');
        $root = N::div(
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
        $text = N::text('mytext');
        $wrong = N::text('other text');
        $root = N::div(
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
        $rightObject = N::div([]);
        $wrongObject = N::div([]);
        $root = N::div([], $rightObject);
        $processor = new Processor($root, [
            [new PathStep(null, $root), new PathStep(0, $wrongObject)]
        ]);
        $this->setExpectedException('NoTee\Exceptions\PathOutdatedException');
        $processor->addClass('class');
    }

    public function test_addClass_nested()
    {
        $root = N::div(
            [],
            N::div(
                [],
                N::div([])
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
