<?php

use PHPUnit\Framework\TestCase;
use Meride\Network\Token;

final class TokenTest extends TestCase
{
    protected function setUp()
    {
        $this->MERIDE_URL = "http://dev7.meride.tv/webink";
        $this->MERIDE_ACCESS_TOKEN = "EtJaOVJxaNoefxiT6lBNza9h8XhQBis5C15gNFEOiTSog18cczQCAQKyDRf50x";
    }
    /**
     * @covers Token::generate
     */
    public function testGenerate()
    {
        $token = new Token($this->MERIDE_ACCESS_TOKEN, $this->MERIDE_URL);
        $token->generate();
        $this->assertNotEmpty($token->accessToken);
        $this->assertNotEmpty($token->refreshToken);
    }
}