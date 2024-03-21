<?php

namespace EightyfourTests\Configuration;

use Eightyfour\Attribute\Config\Auth\Fields;
use Eightyfour\Configuration\Configurator;
use Eightyfour\Core\Core;
use Eightyfour\Core\DotEnv;
use Eightyfour\Exception\AuthenticatorException;
use Eightyfour\Exception\ProviderException;
use Eightyfour\Security\Auth\Authenticator;
use Eightyfour\Security\Auth\Provider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Configurator::class)]
class ConfiguratorTest extends TestCase
{
    public function testsGetDirectoriesBySection(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getDirectories('project');

        // Expects
        $this->assertIsArray(actual: $result);
        $this->assertSame(expected: ['/app'], actual: $result);
    }

    public function testsGetSecurityBySection(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getSecurity('auth');

        // Expects
        $this->assertIsArray(actual: $result);
    }

    private function init(): ?Configurator
    {
        return Configurator::init(
            core: $this->getCore()
        );
    }

    private function getCore(): Core
    {
        return new Core(
            env: DotEnv::getOnlyFrameworkVars()
        );
    }
}