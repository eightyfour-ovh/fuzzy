<?php

namespace Eightyfour\Core;

use Eightyfour\Trait\UtilsTrait;

class Core
{
    use UtilsTrait;

    public function __construct(
        public array $env = []
    ) {
    }

    public static function boot(): ?self
    {
        return new Core(
            env: DotEnv::getOnlyFrameworkVars()
        );
    }
}