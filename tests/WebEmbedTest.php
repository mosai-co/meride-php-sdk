<?php

use PHPUnit\Framework\TestCase;
use Meride\Web\Embed;

final class WebEmbedTest extends TestCase
{

    public function testPresumeBaseUrlV1()
    {
        $this->assertStringContainsString('mediamosaico', Embed::presumeBaseURL('mosaico', 'v1'));
    }

    public function testPresumeBaseUrlV2()
    {
        $this->assertStringContainsString('data.meride.tv', Embed::presumeBaseURL('mosaico', 'v2'));
    }

    public function testPresumeBaseIframeUrlV1()
    {
        $iframeBaseURL = Embed::presumeBaseIframeURL('mosaico', 'v1');
        $this->assertStringContainsString('mediamosaico', $iframeBaseURL);
        $this->assertStringContainsString('iframe.php', $iframeBaseURL);
    }

    public function testPresumeBaseIframeUrlV2()
    {
        $iframeBaseURL = Embed::presumeBaseIframeURL('mosaico', 'v2');
        $this->assertStringContainsString('data.meride.tv', $iframeBaseURL);
        $this->assertStringContainsString('iframe.php', $iframeBaseURL);
    }

    public function testPresumeIframeUrlV1()
    {
        $iframeBaseURL = Embed::presumeIframeURL([
            'clientID' => 'mosaico',
            'embedID' => 123
        ], 'v1');
        $this->assertStringContainsString('mediamosaico', $iframeBaseURL);
        $this->assertStringContainsString('iframe.php', $iframeBaseURL);
        $this->assertStringContainsString('123', $iframeBaseURL);
    }

    public function testPresumeIframeUrlV2()
    {
        $iframeBaseURL = Embed::presumeIframeURL([
            'clientID' => 'mosaico',
            'embedID' => 123
        ], 'v2');
        $this->assertStringContainsString('data.meride.tv', $iframeBaseURL);
        $this->assertStringContainsString('iframe.php', $iframeBaseURL);
        $this->assertStringContainsString('123', $iframeBaseURL);
    }

    public function testPresumeIframeUrlWithBulkLabelV1()
    {
        $iframeBaseURL = Embed::presumeIframeURL([
            'clientID' => 'mosaico',
            'embedID' => 123,
            'bulkLabel' => 'testlabel'
        ], 'v1');
        $this->assertStringContainsString('mediamosaico', $iframeBaseURL);
        $this->assertStringContainsString('iframe.php', $iframeBaseURL);
        $this->assertStringContainsString('123', $iframeBaseURL);
        $this->assertStringContainsString('testlabel', $iframeBaseURL);
    }

    public function testPresumeIframeUrlWithBulkLabelV2()
    {
        $iframeBaseURL = Embed::presumeIframeURL([
            'clientID' => 'mosaico',
            'embedID' => 123,
            'bulkLabel' => 'testlabel'
        ], 'v2');
        $this->assertStringContainsString('data.meride.tv', $iframeBaseURL);
        $this->assertStringContainsString('iframe.php', $iframeBaseURL);
        $this->assertStringContainsString('123', $iframeBaseURL);
        $this->assertStringContainsString('testlabel', $iframeBaseURL);
    }
    

    public function testGetBaseUrlV1()
    {
        $this->assertEquals(Embed::presumeIframeURL(array(
            'embedID' => '1595',
            'clientID' => 'webink',
            'width' => '640',
            'height' => '400',
            'bulkLabel' => 'testLabel'
        ), 'v1'), 'https://mediawebink-meride-tv.akamaized.net/proxy/iframe.php/1595/webink/testLabel');
    }

    public function testGetBaseUrlV2()
    {
        $this->assertEquals(Embed::presumeIframeURL(array(
            'embedID' => '1595',
            'clientID' => 'webink',
            'width' => '640',
            'height' => '400',
            'bulkLabel' => 'testLabel'
        ), 'v2'), 'https://data.meride.tv/proxy/iframe.php/1595/webink/testLabel');
    }

    public function testPresumeScriptUrlV1()
    {
        $this->assertEquals(Embed::presumeScriptURL('mosaico', 'v1'), 'https://mediamosaico-meride-tv.akamaized.net/scripts/latest/embed.js');
    }

    public function testPresumeScriptUrlV2()
    {
        $this->assertEquals(Embed::presumeScriptURL('mosaico', 'v2'), 'https://data.meride.tv/scripts/latest/embed.js');
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