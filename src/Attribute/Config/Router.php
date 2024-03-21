<?php

namespace Eightyfour\Attribute\Config;

use Attribute;
use Eightyfour\Attribute\EightyfourAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Router extends EightyfourAttribute
{
    public function __construct(
        private readonly array $directories = [],
    ) {
    }

    public function getDirectories(): array
    {
        return $this->directories;
    }
}