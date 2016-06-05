<?php

namespace NoTee;


class EventTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $nf = new NodeFactory('utf-8');

        $expected = $nf->form(
            ['class' => 'someclass anotherclass', 'id' => 'someid'],
            $nf->input(['type' => 'hidden', 'value' => '12345', 'name' => 'xsrf_token']),
            $nf->div('onAttr')
        );

        $nf->onAttr('id', 'someid', function($attributes, $children) use ($nf) {
            $children[] = $nf->div('onAttr');
            return [$attributes, $children];
        });

        $nf->onClass('someclass', function($attributes, $children) {
            $attributes['class'] = $attributes['class'] . ' anotherclass';
            return [$attributes, $children];
        });

        $nf->onTag('form', function($attributes, $children) use ($nf){
            $children[] = $nf->input(['type' => 'hidden', 'value' => '12345', 'name' => 'xsrf_token']);
            return [$attributes, $children];
        });

        $node = $nf->form(
            ['class' => 'someclass', 'id' => 'someid']
        );

        $this->assertEquals((string)$expected, (string)$node);

    }
}
