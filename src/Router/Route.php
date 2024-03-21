<?php

namespace Eightyfour\Router;

use Eightyfour\Attribute\Router\Route as AttrRoute;
use Eightyfour\Core\Response\Render;

class Route  extends AttrRoute
{
    public function __construct(
        string|array|null $path = null,
        readonly ?string $name = null,
        array|string $methods = [],
        private readonly ?array $controller = null,
        private ?Render $render = null,
    ) {
        parent::__construct(
            path: $path,
            name: $name,
            methods: $methods
        );
    }

    public function getController(): ?array
    {
        return $this->controller;
    }

    public function getRender(): ?Render
    {
        return $this->render;
    }

    public function setRender(?Render $render = null): Route
    {
        $this->render = $render;

        return $this;
    }
}