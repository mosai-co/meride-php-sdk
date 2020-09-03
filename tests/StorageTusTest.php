<?php

use PHPUnit\Framework\TestCase;
use Meride\Storage\Tus\Client;

final class StorageTusTest extends TestCase
{
    public function testFirstInstanceDefaultData()
    {
        $tusClient = new Client();
        $this->assertEquals('', $tusClient->getUploadToken());
        $this->assertEquals('https://storageapi.meride.tv', $tusClient->getServiceBasePath());
        $this->assertEquals('uploads/files', $tusClient->getUploadPath());
        $this->assertEquals('https', $tusClient->getProtocol());
        $this->assertTrue(is_array($tusClient->getOptions()));
        $this->assertArrayHasKey('headers', $tusClient->getOptions());
    }
    public function testFirstInstanceCustomData()
    {
        $uploadToken = "1234";
        $storageServiceBasePath = "https://www.test.com/testroute";
        $uploadPath = 'uploading/files';
        $tusClient = new Client($uploadToken, $storageServiceBasePath, $uploadPath);
        $this->assertEquals($uploadToken, $tusClient->getUploadToken());
        $this->assertEquals($storageServiceBasePath, $tusClient->getServiceBasePath());
        $this->assertEquals($uploadPath, $tusClient->getUploadPath());
    }
}