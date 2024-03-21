<?php

namespace EightyfourTests\Attribute\Router;

use Eightyfour\Attribute\Router\Route;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Route::class)]
class RouteTest extends TestCase
{
    public function testsGetPath(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getPath();

        // Expects
        $this->assertSame(expected: '/', actual: $result);
    }

    public function testsGetName(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getName();

        // Expects
        $this->assertSame(expected: 'index', actual: $result);
    }

    public function testsGetMethods(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getMethods();

        // Expects
        $this->assertIsArray(actual: $result);
        $this->assertSame(expected: 'GET', actual: $result[0]);
    }

    private function init(): Route
    {
        return new Route(
            path: '/',
            name: 'index',
            methods: ['GET']
        );
    }
}