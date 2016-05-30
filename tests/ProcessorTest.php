<?php

namespace NoTee;

use Satooshi\Component\File\Path;

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

    public function test_children()
    {
        $root = $this->nf->div(
            $this->nf->div(
                $this->nf->div(),
                $this->nf->span('mscho')
            )
        );

        $processor = new Processor($root, [
            [new PathStep(0, $root), new PathStep(0, $root->getChildren()[0])],
        ]);

        $newRoot = $processor->children()->addClass('class')->getRoot();
        $this->assertEquals('<div><div><div class="class" /><span class="class">mscho</span></div></div>', (string)$newRoot);
    }

    public function test_setRaw()
    {
        $root = $this->nf->div(
            $this->nf->raw('<div></div>')
        );
        $processor = new Processor($root, [[new PathStep(0, $root), new PathStep(0, $root->getChildren()[0])]]);
        $root = $processor->setRaw('replacement')->getRoot();
        $this->assertEquals('<div>replacement</div>', (string)$root);
    }

    public function test_setText()
    {
        $root = $this->nf->div(
            'original'
        );
        $processor = new Processor($root, [[new PathStep(0, $root), new PathStep(0, $root->getChildren()[0])]]);
        $root = $processor->setText('replacement')->getRoot();
        $this->assertEquals('<div>replacement</div>', (string)$root);
    }

    public function test_invalidPathIndex_throwsException()
    {
        $root = $this->nf->div(
            $this->nf->raw('<div></div>')
        );
        $processor = new Processor($root, [
            [new PathStep(0, $root), new PathStep(1, $root->getChildren()[0])]
        ]);
        $this->setExpectedException('NoTee\Exceptions\PathOutdatedException');
        $processor->addClass('test');
    }
    
    public function test_insertAfter_and_insertBefore()
    {
        $td = $this->nf->td(
            'test'
        );
        $tr = $this->nf->tr(
            $td
        );
        $table = $this->nf->table(
            $tr
        );

        $root = $this->nf->div(
            $this->nf->div(
                $this->nf->div(),
                $this->nf->span('mscho')
            ),
            $table
        );
        $pathSteps = [
            [new PathStep(0, $root), new PathStep(0, $root->getChildren()[0])],
            [
                new PathStep(0, $root),
                new PathStep(0, $root->getChildren()[0]),
                new PathStep(0, $root->getChildren()[0]->getChildren()[0])
            ],
            [
                new PathStep(0, $root),
                new PathStep(1, $table),
                new PathStep(0, $tr),
                new PathStep(0, $td)
            ]
        ];
        $node = $this->nf->div('test');

        $processor = new Processor($root, $pathSteps);
        $newRoot = $processor->insertAfter($node)->getRoot();
        $this->assertEquals('<div><div><div /><div>test</div><span>mscho</span></div><div>test</div><table><tr><td>test</td><div>test</div></tr></table></div>', (string)$newRoot);

        $processor = new Processor($root, $pathSteps);
        $newRoot = $processor->insertBefore($node)->getRoot();
        $this->assertEquals('<div><div>test</div><div><div>test</div><div /><span>mscho</span></div><table><tr><div>test</div><td>test</td></tr></table></div>', (string)$newRoot);

        $newRoot = $processor->addClass('test')->getRoot();
        $this->assertEquals('<div><div>test</div><div class="test"><div>test</div><div class="test" /><span>mscho</span></div><table><tr><div>test</div><td class="test">test</td></tr></table></div>', (string)$newRoot);

        $root = $this->nf->div(
            $this->nf->div()
        );
        $processor = new Processor($root, [
            [new PathStep(0, $root), new PathStep(0, $root->getChildren()[0])]
        ]);
        $node = $this->nf->table();
        $newRoot = $processor->insertAfter($node)->insertAfter($node)->insertBefore($node)->getRoot();
        $this->assertEquals('<div><table /><div /><table /><table /></div>', (string)$newRoot);
    }

}
