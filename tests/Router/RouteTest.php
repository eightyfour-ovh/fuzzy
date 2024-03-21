<?php

namespace EightyfourTests\Router;

use Eightyfour\Core\Response\Render;
use Eightyfour\Router\Route;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Route::class)]
class RouteTest extends TestCase
{
    public function testsGetController(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getController();

        // Expects
        $this->assertIsArray(actual: $result);
        $this->assertSame(expected: ['className' => 'Controller', 'method' => 'index'], actual: $result);
    }

    public function testsGetRender(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getRender();

        // Expects
        $this->assertInstanceOf(expected: Render::class, actual: $result);
    }

    public function testsSetRender(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->setRender();

        // Expects
        $this->assertNull(actual: $result->getRender());
    }

    private function init(): Route
    {
        return new Route(
            controller: ['className' => 'Controller', 'method' => 'index'],
            render: $this->createMock(originalClassName: Render::class)
        );
    }
}