<?php

namespace EightyfourTests\Attribute\Config;

use Eightyfour\Attribute\Config\Auth\Authenticator;
use Eightyfour\Attribute\Config\Auth\Fields;
use Eightyfour\Attribute\Config\Auth\Provider;
use Eightyfour\Attribute\Config\Authentication;
use Eightyfour\Exception\Exception;
use Eightyfour\Security\Auth\Authenticator as DefaultAuthenticator;
use Eightyfour\Security\Auth\Provider as DefaultProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Authentication::class)]
class AuthenticationTest extends TestCase
{
    public function testsIsEnabled(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->isEnabled();

        // Expects
        $this->assertTrue(condition: $result);
    }

    public function testsGetProvider(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getProvider();

        // Expects
        $this->assertInstanceOf(expected: Provider::class, actual: $result);
    }

    public function testsGetAuthenticator(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getAuthenticator();

        // Expects
        $this->assertInstanceOf(expected: Authenticator::class, actual: $result);
    }

    private function init(): Authentication
    {
        return new Authentication(
            enabled: true,
            provider: $this->createMock(originalClassName: Provider::class),
            authenticator: $this->createMock(originalClassName: Authenticator::class)
        );
    }
}