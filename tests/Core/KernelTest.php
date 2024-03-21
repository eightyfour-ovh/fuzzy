<?php

namespace EightyfourTests\Core;

use Eightyfour\Configuration\Configurator;
use Eightyfour\Core\Core;
use Eightyfour\Core\Kernel;
use Eightyfour\Core\Request\Request;
use Eightyfour\Core\Response\Render;
use Eightyfour\Core\Response\Response;
use Eightyfour\Exception\Exception;
use Eightyfour\Router\Route;
use Eightyfour\Router\Router;
use Eightyfour\Security\System;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Kernel::class)]
class KernelTest extends TestCase
{
    public function testsGetEnv(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getEnv();

        // Expects
        $this->assertSame(expected: 'dev', actual: $result);
    }

    public function testsIsDebug(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->isDebug();

        // Expects
        $this->assertTrue(condition: $result);
    }

    public function testsGetCore(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getCore();

        // Expects
        $this->assertInstanceOf(expected: Core::class, actual: $result);
    }

    public function testsGetConf(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getConf();

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

    public function testsSetAndGetRequest(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->setRequest(request: new Request());

        // Expects
        $this->assertInstanceOf(expected: Request::class, actual: $result?->getRequest());
    }

    public function testsSetAndGetResponse(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->setResponse(response: new Response());

        // Expects
        $this->assertInstanceOf(expected: Response::class, actual: $result?->getResponse());
    }

    public function testsGetSystem(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getSystem();

        // Expects
        $this->assertInstanceOf(expected: System::class, actual: $result);
    }

    public function testsHandleAndTerminate(): void
    {
        // Given
        $core = $this->createMock(originalClassName: Core::class);
        $config = $this->createMock(originalClassName: Configurator::class);
        $router = $this->createMock(originalClassName: Router::class);
        $system = $this->createMock(originalClassName: System::class);

        $class = new Kernel(
            env: 'test',
            debug: true,
            core: $core,
            conf: $config,
            router: $router,
            system: $system
        );
        $request = $this->createMock(originalClassName: Request::class);
        $render = $this->createMock(originalClassName: Render::class);
        $route = new Route(
            path: '/',
            name: 'index',
            methods: ['GET'],
            controller: [
                Router::CURRENT_CLASSNAME => Router::DEFAULT_CONTROLLER,
                Router::CURRENT_METHOD => Router::DEFAULT_METHOD,
            ],
            render: $render
        );
        $reflection = $this->createMock(originalClassName: \ReflectionClass::class);

        // Then
        $reflection->expects($this->any())
            ->method(constraint: 'getName')
            ->willReturn(value: Router::DEFAULT_CONTROLLER)
        ;
        $render->expects($this->any())
            ->method(constraint: 'getClass')
            ->willReturn(value: $reflection)
        ;
        $render->expects($this->any())
            ->method(constraint: 'getMethod')
            ->willReturn(value: Router::DEFAULT_METHOD)
        ;
        $system->expects($this->any())
            ->method(constraint: 'getCurrent')
            ->willReturn(value: $route)
        ;
        $router->expects($this->any())
            ->method(constraint: 'getRendering')
            ->withAnyParameters()
            ->willReturn(value: $render)
        ;
        $system->expects($this->any())
            ->method(constraint: 'getRouter')
            ->willReturn(value: $router)
        ;

        // When
        $result = $class->handle(request: $request);
        $class->terminate();

        // Expects
        $this->assertInstanceOf(expected: Response::class, actual: $result?->getResponse());
    }

    public function testsHandleWithRouteNotFound(): void
    {
        // Given
        $class = $this->init();
        $request = $this->createMock(originalClassName: Request::class);

        // Then
        $this->expectException(exception: Exception::class);

        // When
        $class?->handle(request: $request);
    }

    public function testsTerminateWithNoResponse(): void
    {
        // Given
        $class = $this->init();

        // Then
        $this->expectException(exception: Exception::class);

        // When
        $class?->terminate();
    }

    private function init(): ?Kernel
    {
        return Kernel::boot(prod: false);
    }
}