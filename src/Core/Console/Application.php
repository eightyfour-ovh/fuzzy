<?php

namespace Eightyfour\Core\Console;

use Eightyfour\Core\Kernel;

final readonly class Application
{

    public function __construct(
        private array $args,
        private ?Kernel $kernel = null
    ) {
    }

    public function run(): void
    {
        $args = $this->getArguments();
        $kernel = $this->getKernel();
        // TODO: handle CLI
    }

    public function getArguments(): array
    {
        return $this->args;
    }

    public function getKernel(): ?Kernel
    {
        return $this->kernel;
    }
}