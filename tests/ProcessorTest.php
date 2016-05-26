<?php

namespace NoTee;

class ProcessorTest extends \PHPUnit_Framework_TestCase
{

    /** @var  NodeFactory */
    private $nf;

    /**
     * @before
     */
    public function before()
    {
        $this->nf = new NodeFactory('utf-8', new AttributeValidator(true, true));
    }

    public function test_addClass_doneRight_correctResult()
    {
        $root1 = $this->nf->div(
            ['class' => 'a b c'],
            $this->nf->div(
                ['class' => 'class1 class2']
            ),
            $this->nf->div(['class' => 'classA classB'])
        );

        $processor = new Processor($root1, [
            [new PathStep(null, $root1)]
        ]);
        /** @var DefaultNode $root2 */
        $root2 = $processor->addClass('d')->getRoot();
        $this->assertEquals('<div class="a b c"><div class="class1 class2" /><div class="classA classB" /></div>', $root1->__toString());
        $this->assertEquals('<div class="a b c d"><div class="class1 class2" /><div class="classA classB" /></div>', $root2->__toString());
        $root3 = $processor->addClass('SomeClass')->getRoot();
        $this->assertEquals('<div class="a b c d SomeClass"><div class="class1 class2" /><div class="classA classB" /></div>', $root3->__toString());


        $processor = new Processor($root1, [
            [new PathStep(null, $root1), new PathStep(0, $root1->getChildren()[0])]
        ]);
        $root4 = $processor->addClass('class3')->addClass('class4')->getRoot();
        $this->assertEquals('<div class="a b c"><div class="class1 class2 class3 class4" /><div class="classA classB" /></div>', $root4->__toString());
    }

    public function test_addClass_onTextNode_throwsException()
    {
        $text = $this->nf->text('mytext');
        $root = $this->nf->div(
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
        $raw = $this->nf->raw('mytext');
        $root = $this->nf->div(
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
        $raw = $this->nf->raw('mytext');
        $wrong = $this->nf->raw('other text');
        $root = $this->nf->div(
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
        $text = $this->nf->text('mytext');
        $wrong = $this->nf->text('other text');
        $root = $this->nf->div(
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
        $rightObject = $this->nf->div([]);
        $wrongObject = $this->nf->div([]);
        $root = $this->nf->div([], $rightObject);
        $processor = new Processor($root, [
            [new PathStep(null, $root), new PathStep(0, $wrongObject)]
        ]);
        $this->setExpectedException('NoTee\Exceptions\PathOutdatedException');
        $processor->addClass('class');
    }

    public function test_addClass_nested()
    {
        $root = $this->nf->div(
            [],
            $this->nf->div(
                [],
                $this->nf->div([])
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
        $this->assertEquals('<div><div class="test"><div class="test" /></div></div>', $newRoot->__toString());
    }

}
