<?php

use PHPUnit\Framework\TestCase;
use Meride\Api;

final class MerideTest extends TestCase
{
    protected function setUp()
    {
        $this->MERIDE_URL = "http://dev7.meride.tv/webink";
        $this->MERIDE_AUTH_CODE = "EtJaOVJxaNoefxiT6lBNza9h8XhQBis5C15gNFEOiTSog18cczQCAQKyDRf50x";
        $this->MERIDE_VERSION = "v2";
    }

    public function testGetVideoNotEmpty()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION);
        $this->assertNotEmpty($api->get("video", 919));
    }

    public function testGetVideoNotEmptyVersion2()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION);
        $this->assertNotEmpty($api->get("video", 919));
    }

    public function __testGetVideoJsonContentIsValid()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION);
        $videoResponse = $api->get("video", 919);
        $this->assertInstanceOf(stdClass::class, $videoResponse->jsonContent);
    }

    public function __testGetVideoHasContent()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION);
        $videoResponse = $api->get("video", 919);
        $this->assertNotEmpty($videoResponse->content);
    }
}
