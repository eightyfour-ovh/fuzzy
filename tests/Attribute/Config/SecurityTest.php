<?php

namespace EightyfourTests\Attribute\Config;

use Eightyfour\Attribute\Config\Auth\Authenticator;
use Eightyfour\Attribute\Config\Auth\Fields;
use Eightyfour\Attribute\Config\Auth\Provider;
use Eightyfour\Attribute\Config\Authentication;
use Eightyfour\Attribute\Config\Security;
use Eightyfour\Exception\Exception;
use Eightyfour\Security\Auth\Authenticator as DefaultAuthenticator;
use Eightyfour\Security\Auth\Provider as DefaultProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Security::class)]
class SecurityTest extends TestCase
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

    public function testsGetAuth(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getAuth();

        // Expects
        $this->assertInstanceOf(expected: Authentication::class, actual: $result);
    }

    private function init(): Security
    {
        return new Security(
            enabled: true,
            auth: $this->getAuth()
        );
    }

    private function getAuth(): Authentication
    {
        return new Authentication(
            enabled: true,
            provider: $this->getProvider(),
            authenticator: $this->getAuthenticator()
        );
    }

    private function getProvider(): Provider
    {
        return new Provider(
            enabled: true,
            providers: [DefaultProvider::class],
            default: DefaultProvider::class,
            exceptionType: Exception::class
        );
    }

    private function getAuthenticator(): Authenticator
    {
        return new Authenticator(
            enabled: true,
            authenticators: [DefaultAuthenticator::class],
            default: DefaultAuthenticator::class,
            fields: $this->getFields(),
            exceptionType: Exception::class
        );
    }

    private function getFields(): Fields
    {
        return new Fields(
            login: 'email',
            password: 'password'
        );
    }
}