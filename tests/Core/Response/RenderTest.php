<?php

namespace EightyfourTests\Core\Response;

use Eightyfour\Abstract\AbstractController;
use Eightyfour\Core\Reflection\ControllerLauncher;
use Eightyfour\Core\Response\Render;
use Eightyfour\Router\Route;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Render::class)]
class RenderTest extends TestCase
{
    public function testsInit(): void
    {
        // Given
        $route = new Route(
            path: '/',
            name: 'app_index_home2',
            methods: ['GET'],
            controller: [
                'className' => AbstractController::class,
                'method' => 'home',
            ],
            render: new Render(
                class: new \ReflectionClass(objectOrClass: AbstractController::class),
                method: 'home'
            )
        );
        $controllerLauncher = ControllerLauncher::init(route: $route)
            ->prepareConstructor()
            ->prepareDependencies()
            ->matchParameters()
        ;

        // When
        $result = Render::init(launcher: $controllerLauncher);

        // Then
        $this->assertInstanceOf(expected: Render::class, actual: $result);
    }

    public function testsGetClass(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getClass();

        // Expects
        $this->assertInstanceOf(expected: \ReflectionClass::class, actual: $result);
    }

    public function testsGetMethod(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getMethod();

        // Expects
        $this->assertSame(expected: 'default', actual: $result);
    }

    public function testsGetParentClass(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getParentClass();

        // Expects
        $this->assertNull(actual: $result);
    }

    public function testsGetConstructor(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getConstructor();

        // Expects
        $this->assertNull(actual: $result);
    }

    public function testsGetConstructorParams(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getConstructorParams();

        // Expects
        $this->assertNull(actual: $result);
    }

    public function testsGetParentConstructor(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getParentConstructor();

        // Expects
        $this->assertNull(actual: $result);
    }

    public function testsGetParentConstructorParams(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getParentConstructorParams();

        // Expects
        $this->assertNull(actual: $result);
    }

    private function init(): Render
    {
        return new Render(
            class: $this->createMock(originalClassName: \ReflectionClass::class),
            method: 'default'
        );
    }
}