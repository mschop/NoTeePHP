<?php


namespace NoTee;


use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class DefaultEscaperTest extends TestCase
{
    public function test_invalidEncoding_throwsException()
    {
        $this->expectException(InvalidArgumentException::class);
        new DefaultEscaper('utf--8');
    }
}