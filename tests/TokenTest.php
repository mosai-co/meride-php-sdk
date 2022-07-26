<?php

use PHPUnit\Framework\TestCase;
use Meride\Network\Token;

final class TokenTest extends TestCase
{
    protected function setUp(): void
    {
        $this->MERIDE_URL = getenv('MERIDE_URL');
        $this->MERIDE_AUTH_CODE = getenv('MERIDE_AUTH_CODE');
        $this->MERIDE_VERSION = "v2";
    }
    /**
     * @covers Token::generate
     */
    public function testGenerate()
    {
        $token = new Token($this->MERIDE_AUTH_CODE, $this->MERIDE_URL, $this->MERIDE_VERSION);
        $token->generate();
        $this->assertNotEmpty($token->accessToken);
    }
}