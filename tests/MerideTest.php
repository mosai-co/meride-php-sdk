<?php

use PHPUnit\Framework\TestCase;
use Meride\Api;

final class MerideTest extends TestCase
{
    protected function setUp()
    {
        $this->MERIDE_URL = getenv('MERIDE_URL');
        $this->MERIDE_AUTH_CODE = getenv('MERIDE_AUTH_CODE');
        $this->MERIDE_VERSION_1 = "";
        $this->MERIDE_VERSION_2 = "v2";
        $this->TEST_ID = 919;
    }
    
    public function testGetVideoNotEmptyVersion1()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION_1);
        $this->assertNotEmpty($api->get("video", $this->TEST_ID));
    }

    public function testGetVideoNotEmpty()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION_2);
        $this->assertNotEmpty($api->get("video", $this->TEST_ID));
    }


    public function testGetVideoWithLimit1Counts1()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION_2);
        $videosResponse = $api->get("video", null, array(
            'search_page' => 1,
            'search_for_page' => 1
        ));
        $videos = $videosResponse->jsonContent->data;
        $this->assertCount(1, $videos);
    }

    public function testGetVideoJsonContentIsValidVersion1()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION_1);
        $videoResponse = $api->get("video", $this->TEST_ID);
        $this->assertInstanceOf(stdClass::class, $videoResponse->jsonContent);
        
    }

    public function testGetVideoJsonContentIsValid()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION_2);
        $videoResponse = $api->get("video", $this->TEST_ID);
        $this->assertInstanceOf(stdClass::class, $videoResponse->jsonContent);
    }

    public function testGetVideoHasContent()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION_2);
        $videoResponse = $api->get("video", $this->TEST_ID);
        $this->assertNotEmpty($videoResponse->content);
    }
}
