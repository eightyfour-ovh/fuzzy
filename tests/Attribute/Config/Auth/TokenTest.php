<?php

namespace EightyfourTests\Attribute\Config\Auth;

use Eightyfour\Attribute\Config\Auth\Token;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Token::class)]
class TokenTest extends TestCase
{
    public function testsGetToken(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getToken();

        // Expects
        $this->assertSame(expected: "token", actual: $result);
    }

    private function init(): Token
    {
        return new Token(
            token: 'token'
        );
    }
}