<?php

namespace EightyfourTests\Attribute\Config\Auth;

use Eightyfour\Attribute\Config\Auth\ApiKey;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: ApiKey::class)]
class ApiKeyTest extends TestCase
{
    public function testsGetKey(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getKey();

        // Expects
        $this->assertSame(expected: "myKey", actual: $result);
    }

    public function testsGetSecret(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getSecret();

        // Expects
        $this->assertSame(expected: "mySecret", actual: $result);
    }

    private function init(): ApiKey
    {
        return new ApiKey(
            key: "myKey",
            secret: "mySecret"
        );
    }
}