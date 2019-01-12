<?php

use PHPUnit\Framework\TestCase;
use Meride\Network\Request;

final class RequestTest extends TestCase
{
    public function testClassNew()
    {
        $this->assertInstanceOf(Request::class, new Request());
    }

    public function testClassInstance()
    {
        $this->assertInstanceOf(Request::class, Request::instance());
    }
}