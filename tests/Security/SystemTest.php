<?php

namespace EightyfourTests\Security;

use Eightyfour\Configuration\Configurator;
use Eightyfour\Core\Core;
use Eightyfour\Router\Router;
use Eightyfour\Security\System;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: System::class)]
class SystemTest extends TestCase
{
    public function testsGetCore(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getCore();

        // Expects
        $this->assertInstanceOf(expected: Core::class, actual: $result);
    }

    public function testsGetConfig(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getConfig();

        // Expects
        $this->assertInstanceOf(expected: Configurator::class, actual: $result);
    }

    public function testsGetRouter(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getRouter();

        // Expects
        $this->assertInstanceOf(expected: Router::class, actual: $result);
    }

    public function testsGetCurrent(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getCurrent();

        // Expects
        $this->assertNull(actual: $result);
    }

    private function init(): ?System
    {
        return System::launch(
            core: $this->createMock(originalClassName: Core::class),
            config: $this->createMock(originalClassName: Configurator::class),
            router: $this->createMock(originalClassName: Router::class)
        );
    }
}