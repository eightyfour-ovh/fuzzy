<?php

namespace EightyfourTests\Router;

use Eightyfour\Configuration\Configurator;
use Eightyfour\Router\Loader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Loader::class)]
class LoaderTest extends TestCase
{
    public function testsScan(): void
    {
        // Given
        $class = $this->init();
        $configMock = $this->createMock(originalClassName: Configurator::class);

        // When
        $result = $class->scan(config: $configMock);

        // Expects
        $this->assertSame(expected: [], actual: $result);
    }

    public function testsScanWithoutContent(): void
    {
        // Given
        $class = $this->init();
        $configMock = $this->createMock(originalClassName: Configurator::class);

        // Then
        mkdir(directory: __DIR__ . '/tmp');
        $configMock->expects($this->any())
            ->method(constraint: 'getDirectories')
            ->withAnyParameters()
            ->willReturn(value: [__DIR__ . '/tmp'])
        ;

        // When
        $result = $class->scan(config: $configMock);
        rmdir(directory: __DIR__ . '/tmp');

        // Expects
        $this->assertIsArray(actual: $result);
        $this->assertSame(expected: [], actual: $result);
    }

    public function testsScanWithSubDirectories(): void
    {
        // Given
        $class = $this->init();
        $configMock = $this->createMock(originalClassName: Configurator::class);

        // Then
        $configMock->expects($this->any())
            ->method(constraint: 'getDirectories')
            ->withAnyParameters()
            ->willReturn(value: [__DIR__ . '/../Attribute/Config'])
        ;

        // When
        $result = $class->scan(config: $configMock);

        // Expects
        $this->assertIsArray(actual: $result);
        $this->assertSame(expected: [
            '/app/tests/Attribute/Config/Auth/ApiKeyTest.php',
            '/app/tests/Attribute/Config/Auth/AuthenticatorTest.php',
            '/app/tests/Attribute/Config/Auth/FieldsTest.php',
            '/app/tests/Attribute/Config/Auth/ProviderTest.php',
            '/app/tests/Attribute/Config/Auth/TokenTest.php',
            '/app/tests/Attribute/Config/AuthenticationTest.php',
            '/app/tests/Attribute/Config/RouterTest.php',
            '/app/tests/Attribute/Config/SecurityTest.php',
        ], actual: $result);
    }

    private function init(): Loader
    {
        return new Loader();
    }
}