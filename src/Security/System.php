<?php

namespace Eightyfour\Security;

use Eightyfour\Configuration\Configurator;
use Eightyfour\Core\Core;
use Eightyfour\Router\Route;
use Eightyfour\Router\Router;

class System
{
    public function __construct(
        private readonly ?Core $core = null,
        private readonly ?Configurator $config = null,
        private readonly ?Router $router = null,
        private readonly ?Route $current = null
    ) {
    }
    public static function launch(
        ?Core $core = null,
        ?Configurator $config = null,
        ?Router $router = null,
        ?string $path = null
    ): ?self {
        return new self(
            core: $core,
            config: $config,
            router: $router,
            current: $path !== null ? $router?->getRoute(path: $path) : null
        );
    }

    public function getCore(): ?Core
    {
        return $this->core;
    }

    public function getConfig(): ?Configurator
    {
        return $this->config;
    }

    public function getRouter(): ?Router
    {
        return $this->router;
    }

    public function getCurrent(): ?Route
    {
        return $this->current;
    }
}