<?php

use PHPUnit\Framework\TestCase;
use Meride\Api;

final class MerideTest extends TestCase
{
    protected function setUp()
    {
        $this->MERIDE_URL = "http://dev7.meride.tv/webink";
        $this->MERIDE_ACCESS_TOKEN = "EtJaOVJxaNoefxiT6lBNza9h8XhQBis5C15gNFEOiTSog18cczQCAQKyDRf50x";
        $this->MERIDE_VERSION = "v2";
    }

    public function testGetVideoNotEmpty()
    {
        $api = new Api($this->MERIDE_ACCESS_TOKEN, $this->MERIDE_URL);
        $this->assertNotEmpty($api->get("video", 919));
    }

    public function testGetVideoNotEmptyVersion2()
    {
        $api = new Api($this->MERIDE_ACCESS_TOKEN, $this->MERIDE_URL, $this->MERIDE_VERSION);
        $this->assertNotEmpty($api->get("video", 919));
    }

    public function testGetVideoJsonContentIsValid()
    {
        $api = new Api($this->MERIDE_ACCESS_TOKEN, $this->MERIDE_URL);
        $videoResponse = $api->get("video", 919);
        $this->assertInstanceOf(stdClass::class, $videoResponse->jsonContent);
    }

    public function testGetVideoHasContent()
    {
        $api = new Api($this->MERIDE_ACCESS_TOKEN, $this->MERIDE_URL);
        $videoResponse = $api->get("video", 919);
        $this->assertNotEmpty($videoResponse->content);
    }
}
