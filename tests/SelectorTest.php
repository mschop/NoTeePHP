<?php

namespace NoTee;

class SelectorTest extends \PHPUnit_Framework_TestCase
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
    
    public function test_selectors()
    {
        $div = $this->nf->div([]);
        $a1 = $this->nf->a(['class' => 'a b']);
        $a2 = $this->nf->a([], $a1);
        $p = $this->nf->p([]);
        $p2 = $this->nf->p(['data-something' => 'somewhere']);
        $p3 = $this->nf->p([], $p);
        $subSpan = $this->nf->span([]);
        $span = $this->nf->span([], $subSpan);

        $root = $this->nf->div(
            [],
            $p3,
            $div,
            $a2,
            $p,
            $p2,
            $span
        );

        $results = Selector::select($root, 'div');
        $this->assertCount(2, $results);
        $this->assertEquals($root, $results[0][0]->getNode());
        $this->assertEquals($div, $results[1][1]->getNode());

        $results = Selector::select($root, 'a');
        $this->assertCount(2, $results);
        $this->assertEquals($a2, $results[0][1]->getNode());
        $this->assertEquals($a1, $results[1][2]->getNode());

        $results = Selector::select($root, 'div>a');
        $this->assertCount(1, $results);
        $this->assertEquals($a2, $results[0][1]->getNode());

        $results = Selector::select($root, 'div a.a.b');
        $this->assertCount(1, $results);
        $this->assertEquals($a1, $results[0][2]->getNode());

        $results = Selector::select($root, 'div .c');
        $this->assertCount(0, $results);

        $results = Selector::select($root, 'div a + p');
        $this->assertCount(1, $results);
        $this->assertEquals($p, $results[0][1]->getNode());

        $results = Selector::select($root, 'p + span span');
        $this->assertCount(1, $results);
        $this->assertEquals($subSpan, $results[0][2]->getNode());

        $results = Selector::select($root, 'div a ~ p');
        $this->assertCount(3, $results);
        $this->assertEquals($p3, $results[0][1]->getNode());
        $this->assertEquals($p, $results[1][1]->getNode());
        $this->assertEquals($p2, $results[2][1]->getNode());

        $results = Selector::select($root, 'div a ~ p p');
        $this->assertCount(1, $results);
        $this->assertEquals($p, $results[0][2]->getNode());

        $results = Selector::select($root, 'div, p');
        $this->assertCount(6, $results);

        $results = Selector::select($root, '[data-something=somewhere]');
        $this->assertCount(1, $results);
        $this->assertEquals($p2, $results[0][1]->getNode());
    }

}
