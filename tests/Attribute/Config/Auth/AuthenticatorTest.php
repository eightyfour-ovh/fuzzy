<?php

namespace EightyfourTests\Attribute\Config\Auth;

use Eightyfour\Attribute\Config\Auth\Authenticator;
use Eightyfour\Attribute\Config\Auth\Fields;
use Eightyfour\Exception\Exception;
use Eightyfour\Security\Auth\Authenticator as DefaultAuthenticator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Authenticator::class)]
class AuthenticatorTest extends TestCase
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

    public function testsGetAuthenticators(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getAuthenticators();

        // Expects
        $this->assertIsArray(actual: $result);
        $this->assertSame(expected: DefaultAuthenticator::class, actual: $result[0]);
    }

    public function testsGetDefault(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getDefault();

        // Expects
        $this->assertSame(expected: DefaultAuthenticator::class, actual: $result);
    }

    public function testsGetFields(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getFields();

        // Expects
        $this->assertInstanceOf(expected: Fields::class, actual: $result);
    }

    public function testsGetExceptionType(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getExceptionType();

        // Expects
        $this->assertSame(expected: Exception::class, actual: $result);
    }

    private function init(): Authenticator
    {
        return new Authenticator(
            enabled: true,
            authenticators: [DefaultAuthenticator::class],
            default: DefaultAuthenticator::class,
            fields: $this->createMock(originalClassName: Fields::class),
            exceptionType: Exception::class
        );
    }
}