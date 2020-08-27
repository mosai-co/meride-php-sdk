<?php

use PHPUnit\Framework\TestCase;
use Meride\Api;

final class MerideTest extends TestCase
{
    protected function setUp()
    {
        $this->MERIDE_URL = getenv('MERIDE_URL');
        $this->MERIDE_AUTH_CODE = getenv('MERIDE_AUTH_CODE');
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

    public function testGetVideoWithLimit1Counts1()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION);
        $videosResponse = $api->get("video", null, array(
            'search_page' => 1,
            'search_for_page' => 1
        ));
        $videos = $videosResponse->jsonContent->data;
        $this->assertCount(1, $videos);
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
