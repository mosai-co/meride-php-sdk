<?php

use PHPUnit\Framework\TestCase;
use Meride\Storage\Tus\Client;
use Meride\Storage\Tus\Token;

final class StorageTusTest extends TestCase
{
    protected function setUp(): void
    {
        $this->STORAGE_PROTOCOL = getenv('MERIDE_STORAGE_PROTOCOL');
        if (empty($STORAGE_PROTOCOL)) {
            $this->STORAGE_PROTOCOL = "https";
        }
    }
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
    public function testTokenIsNotEmpty()
    {
        $tokenGenerator = new Token(getenv('MERIDE_AUTH_USER'), getenv('MERIDE_AUTH_CODE'), getenv('MERIDE_STORAGESERVICE_URL'));
        $token = '';
        try {
            $token = $tokenGenerator->generate();
        } catch(\Exception $e) {}
        $this->assertNotEmpty($token);
    }
    public function testUpload()
    {
        $videoOrigin = __DIR__."/assets/small.mp4";
        $tokenGenerator = new Token(getenv('MERIDE_AUTH_USER'), getenv('MERIDE_AUTH_CODE'), getenv('MERIDE_STORAGESERVICE_URL'));
        try {
            $token = $tokenGenerator->generate();
        } catch(\Exception $e) {}
        $this->assertNotEmpty($token);
        $tusClient = new Client($token, getenv('MERIDE_STORAGESERVICE_URL'));
        $tusClient->setProtocol($this->STORAGE_PROTOCOL);
        $uploadUrl = null;
        try {
            $uploadUrl = $tusClient->upload($videoOrigin, 'videotest.mp4');
        } catch (\Exception $e) {}
        $this->assertTrue(is_string($uploadUrl), 'Upload URL is a string');
        $this->assertMatchesRegularExpression('/uploads\/files/', $uploadUrl);
        $extractedUrl = $tusClient->extractURL($uploadUrl);
        $this->assertMatchesRegularExpression('/file/', $extractedUrl);

    }
    public function testUploadDirect()
    {
        $videoOrigin = __DIR__."/assets/small.mp4";
        $tokenGenerator = new Token(getenv('MERIDE_AUTH_USER'), getenv('MERIDE_AUTH_CODE'), getenv('MERIDE_STORAGESERVICE_URL'));
        try {
            $token = $tokenGenerator->generate();
        } catch(\Exception $e) {}
        $this->assertNotEmpty($token);
        $tusClient = new Client($token, getenv('MERIDE_STORAGESERVICE_URL'));
        $tusClient->setProtocol($this->STORAGE_PROTOCOL);
        $uploadUrl = null;
        try {
            $uploadUrl = $tusClient->uploadDirect($videoOrigin);
        } catch (\Exception $e) {}
        $this->assertTrue(is_string($uploadUrl), 'Upload URL is a string');
        $this->assertMatchesRegularExpression('/uploads\/files/', $uploadUrl);
        $extractedUrl = $tusClient->extractURL($uploadUrl);
        $this->assertMatchesRegularExpression('/file/', $extractedUrl);

    }
}