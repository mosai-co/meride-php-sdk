<?php

use PHPUnit\Framework\TestCase;
use Meride\Network\Token;

final class TokenTest extends TestCase
{
    protected function setUp()
    {
        $this->MERIDE_URL = "http://dev7.meride.tv/webink";
        $this->MERIDE_AUTH_CODE = "EtJaOVJxaNoefxiT6lBNza9h8XhQBis5C15gNFEOiTSog18cczQCAQKyDRf50x";
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