<?php

use PHPUnit\Framework\TestCase;
use Meride\Api;
use Meride\MerideEntity;

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
        $videos = $api->search("video", array(
            'search_page' => 1,
            'search_for_page' => 1
        ));
        $this->assertCount(1, $videos);
    }

    public function testGetVideoJsonContentIsValidVersion1()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION_1);
        $videoResponse = $api->get("video", $this->TEST_ID);
        $this->assertInstanceOf('Meride\MerideEntity', $videoResponse);
        
    }

    public function testGetVideoJsonContentIsValid()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION_2);
        $videoResponse = $api->get("video", $this->TEST_ID);
        $this->assertInstanceOf('Meride\MerideEntity', $videoResponse);
    }

    public function testGetVideoHasContent()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION_2);
        $videoResponse = $api->get("video", $this->TEST_ID);
        $this->assertEquals($this->TEST_ID, $videoResponse->id);
    }

    public function testCreateVideoAndDeleteIt()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION_2);
        // copy a renamed video to avoid name duplication and error in the CMS
        $randNum = rand(0, 999999);
        $videoOrigin = __DIR__."/assets/small.mp4";
        $videoCopyRenamed = __DIR__."/assets/small".$randNum.".mp4";
        copy($videoOrigin, $videoCopyRenamed);
        $video = new \CurlFile($videoCopyRenamed);
        $videoResponse = $api->create('video', array(
            'title' => "Test video ".$randNum,
            'video' => $video
        ));
        unlink($videoCopyRenamed);
        $this->assertInstanceOf('Meride\MerideEntity', $videoResponse);
        $apiResponse = $videoResponse->getApiResponse();
        $this->assertEquals(201, $apiResponse->httpCode);
        $this->assertEmpty($apiResponse->error);
        $this->assertNotEmpty($videoResponse->id);
        $videoDeleteResponse = $api->delete('video', $videoResponse->id);
        $this->assertEquals(200, $videoDeleteResponse->httpCode);
        $this->assertInstanceOf('Meride\Network\Response', $videoDeleteResponse);
        $this->assertEquals('delete', $videoDeleteResponse->content);
    }

    public function testReadMultipleWithLimit()
    {
        $api = new Api($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION_2);
        $videos = $api->all('video', [
            'search_page' => 1,
            'search_for_page' => 2
        ]);
        $this->assertInstanceOf('Meride\MerideCollection', $videos);
        $this->assertCount(2, $videos);
        $this->assertEquals(2, $videos->count());
    }
}
