<?php

namespace EightyfourTests\Router;

use Eightyfour\Configuration\Configurator;
use Eightyfour\Core\Core;
use Eightyfour\Core\DotEnv;
use Eightyfour\Core\Response\Render;
use Eightyfour\Router\Route;
use Eightyfour\Router\Router;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Router::class)]
class RouterTest extends TestCase
{
    public function testsGetRouteByName(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getRoute(name: 'fuzzy_unit-tests_default');

        // Expects
        $this->assertInstanceOf(expected: Route::class, actual: $result);
        $this->assertSame(expected: 'fuzzy_unit-tests_default', actual: $result->getName());
    }

    public function testsGetRouteByPath(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getRoute(path: '/_fuzzy_tests');

        // Expects
        $this->assertInstanceOf(expected: Route::class, actual: $result);
        $this->assertSame(expected: '/_fuzzy_tests', actual: $result->getPath());
    }

    public function testsGetRouteByNameAndPath(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getRoute(name: 'fuzzy_unit-tests_default', path: '/_fuzzy_tests');

        // Expects
        $this->assertInstanceOf(expected: Route::class, actual: $result);
        $this->assertSame(expected: 'fuzzy_unit-tests_default', actual: $result->getName());
        $this->assertSame(expected: '/_fuzzy_tests', actual: $result->getPath());
    }

    public function testsGetRouteIsNull(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getRoute();

        // Expects
        $this->assertNull(actual: $result);
    }

    public function testsGetRendering(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getRendering(current: new Route(
            path: '/',
            name: 'app_index_home',
            methods: ['GET', 'POST']
        ));

        // Expects
        $this->assertInstanceOf(expected: Render::class, actual: $result);
    }

    private function init(): ?Router
    {
        return Router::init(
            core: $this->getCore(),
            config: $this->getConfig()
        );
    }

    private function getConfig(): ?Configurator
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