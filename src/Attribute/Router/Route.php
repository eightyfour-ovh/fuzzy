<?php

namespace Eightyfour\Attribute\Router;

use Attribute;
use Eightyfour\Attribute\EightyfourAttribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Route extends EightyfourAttribute
{
    public function __construct(
        public string|array|null $path = null,
        private readonly ?string $name = null,
        public array|string $methods = []
    ) {
    }

    public function getPath(): array|string|null
    {
        return $this->path;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getMethods(): array|string
    {
        return $this->methods;
    }
}