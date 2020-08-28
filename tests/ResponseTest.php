<?php

use PHPUnit\Framework\TestCase;
use Meride\Network\Response;

final class ResponseTest extends TestCase
{
    public function testThrowExceptionOnEmptyParameters()
    {
        $this->expectException(ArgumentCountError::class);
        new Response();
    }
    public function testClassNew()
    {
        $this->assertInstanceOf(Response::class, new Response("{id:2}", false, null));
    }
}