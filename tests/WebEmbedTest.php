<?php

use PHPUnit\Framework\TestCase;
use Meride\Web\Embed;

final class WebEmbedTest extends TestCase
{
    public function testGetBaseURL()
    {
        $this->assertEquals(Embed::presumeIframeURL(array(
            'embedID' => '1595',
            'clientID' => 'webink',
            'width' => '640',
            'height' => '400',
            'bulkLabel' => 'testLabel'
        )), 'https://mediawebink-meride-tv.akamaized.net/proxy/iframe.php/1595/webink/testLabel');
    }

    public function testIframeCode()
    {
        $this->assertNotEmpty(Embed::iframe(array(
            'embedID' => '1595',
            'clientID' => 'webink',
            'width' => '640',
            'height' => '400',
            'bulkLabel' => 'testLabel'
        )));
    }

    public function testAmpIframeCode()
    {
        $this->assertNotEmpty(Embed::ampiframe(array(
            'embedID' => '1595',
            'clientID' => 'webink',
            'width' => '640',
            'height' => '400',
            'bulkLabel' => 'testLabel'
        )));
    }

    public function testDivOnlyCode()
    {
        $this->assertNotEmpty(Embed::divOnly(array(
            'embedID' => '1595',
            'clientID' => 'webink',
            'width' => '640',
            'height' => '400',
            'bulkLabel' => 'testLabel'
        )));
    }

    public function testDivCode()
    {
        $this->assertNotEmpty(Embed::div(array(
            'embedID' => '1595',
            'clientID' => 'webink',
            'width' => '640',
            'height' => '400',
            'bulkLabel' => 'testLabel'
        )));
    }
}