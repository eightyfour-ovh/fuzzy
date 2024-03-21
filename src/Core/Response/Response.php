<?php

namespace Eightyfour\Core\Response;

use Eightyfour\Core\Request\Request;
use Eightyfour\Router\Route;
use Eightyfour\Security\System;

class Response
{
    public function __construct(
        private ?Render $render = null
    ) {
    }

    public static function newInstance(?System $system = null, ?Request $request = null): self
    {
        $current = $system?->getCurrent();
        $render = $system?->getRouter()?->getRendering(request: $request, current: $current);

        return new self(
            render: $render
        );
    }

    public function getRender(): ?Render
    {
        return $this->render;
    }

    public function setRender(Render $render): void
    {
        $this->render = $render;
    }
}