<?php

namespace EightyfourTests\Attribute\Config\Auth;

use Eightyfour\Attribute\Config\Auth\Provider;
use Eightyfour\Exception\Exception;
use Eightyfour\Security\Auth\Provider as DefaultProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Provider::class)]
class ProviderTest extends TestCase
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

    public function testsGetProviders(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getProviders();

        // Expects
        $this->assertIsArray(actual: $result);
        $this->assertSame(expected: DefaultProvider::class, actual: $result[0]);
    }

    public function testsGetDefault(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getDefault();

        // Expects
        $this->assertSame(expected: DefaultProvider::class, actual: $result);
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

    private function init(): Provider
    {
        return new Provider(
            enabled: true,
            providers: [DefaultProvider::class],
            default: DefaultProvider::class,
            exceptionType: Exception::class
        );
    }
}