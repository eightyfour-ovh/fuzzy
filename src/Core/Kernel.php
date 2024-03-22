<?php

namespace Eightyfour\Core;

use Eightyfour\Abstract\AbstractKernel;
use Eightyfour\Configuration\Configurator;
use Eightyfour\Core\Reflection\ControllerLauncher;
use Eightyfour\Core\Request\Request;
use Eightyfour\Core\Response\Render;
use Eightyfour\Core\Response\Response;
use Eightyfour\Core\Response\Result;
use Eightyfour\Exception\Exception;
use Eightyfour\Interface\KernelInterface;
use Eightyfour\Interface\MicroKernelInterface;
use Eightyfour\Router\Router;
use Eightyfour\Security\System;
use Override;

class Kernel extends AbstractKernel implements KernelInterface, MicroKernelInterface
{
    public const string ENV_DEV = 'dev';
    public const string ENV_PROD = 'prod';
    public const string ENV_TEST = 'test';
    public const array ENV = [
        self::ENV_DEV => self::ENV_DEV,
        self::ENV_PROD => self::ENV_PROD,
        self::ENV_TEST => self::ENV_TEST,
    ];

    public function __construct(
        private readonly ?string $env = 'prod',
        private readonly bool $debug = false,
        private readonly ?Core $core = null,
        private readonly ?Configurator $conf = null,
        private readonly ?Router $router = null,
        private readonly ?System $system = null,
        private ?Request $request = null,
        private ?Response $response = null
    ) {
    }

    #[Override] public static function boot(bool $prod = true): ?self
    {
        $core = Core::boot();
        $config = Configurator::init(core: $core);
        $router = Router::init(core: $core, config: $config);
        $system = System::launch(
            core: $core,
            config: $config,
            router: $router,
            path: Request::getRequestedUri()
        );

        return new self(
            env: $prod ? 'prod' : 'dev',
            debug: !$prod,
            core: $core,
            conf: $config,
            router: $router,
            system: $system
        );
    }

    public function getEnv(): ?string
    {
        return $this->env;
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }

    public function getCore(): ?Core
    {
        return $this->core;
    }

    public function getConf(): ?Configurator
    {
        return $this->conf;
    }

    public function getRouter(): ?Router
    {
        return $this->router;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request = null): Kernel
    {
        $this->request = $request;

        return $this;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response = null): Kernel
    {
        $this->response = $response;

        return $this;
    }

    public function getSystem(): ?System
    {
        return $this->system;
    }

    #[Override] public function handle(Request $request): self
    {
        if ($this->system?->getCurrent() === null) {
            // TODO: handle route not found
            throw new Exception(message: 'route not found.');
        }
        $this->request = $request;
        $this->response = Response::newInstance(system: $this->system, request: $request);

        // TODO: handle the REQUEST

        return $this;
    }

    #[Override] public function terminate(): void
    {
        $response = $this->getResponse();
        if ($response === null) {
            // TODO: handle no response available
            throw new Exception(message: 'no response available');
        }

        $route = $this->getSystem()?->getCurrent();
        $routeController = $route?->getController();
        $routeClassName = !empty($routeController) ?
            $routeController[Router::CURRENT_CLASSNAME] : Router::DEFAULT_CONTROLLER;
        $routeMethod = !empty($routeController) ?
            $routeController[Router::CURRENT_METHOD] : Router::DEFAULT_METHOD;
        $render = $this->getResponse()?->getRender();
        if (!$this->checkRendering(
            routeClassName: $routeClassName,
            routeMethod: $routeMethod,
            render: $render
        )) {
            // TODO: handle not the same Controller/method
            throw new Exception(message: 'not the same Controller/method'); // @codeCoverageIgnore
        }

        // @codeCoverageIgnoreStart
        if (!DotEnv::isTest()) {
            $class = ControllerLauncher::handleDependencies(className: $routeClassName);
            // TODO: handle method parameters+request
            /** @var Result $result */
            $result = call_user_func_array([$class, $routeMethod], []); // @phpstan-ignore-line
            $json = json_encode(value: $result->getData()) ?: '';

            printf(format: $json);
        }
        // @codeCoverageIgnoreEnd
    }

    private function checkRendering(
        string $routeClassName,
        string $routeMethod,
        ?Render $render = null
    ): bool {
        $renderClassName = $render?->getClass()?->getName();
        $renderMethod = $render?->getMethod();

        return $routeClassName === $renderClassName && $routeMethod === $renderMethod;
    }
}