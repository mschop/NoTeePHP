<?php

namespace NoTee;


class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function test_map()
    {
        $this->assertEquals([
            2,
            3,
            4,
            5,
        ], Helper::map([
            1,
            2,
            3,
            4,
        ], function($key, $value) {
            return ++$value;
        }));

        $this->assertEquals([
            2 => 2,
            3 => 3,
            4 => 4,
        ], Helper::map([
            2 => 1,
            3 => 2,
            4 => 3,
        ], function($key, $value) {
            return ++$value;
        }, true));
    }
}
