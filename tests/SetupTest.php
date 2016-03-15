<?php

namespace NoTee;


class SetupTest extends \PHPUnit_Framework_TestCase
{
    public function test_ping_pong()
    {
        $this->assertEquals('pong', Setup::ping());
    }
}
