<?php

namespace Eightyfour\Attribute\Config\Auth;

use Attribute;
use Eightyfour\Attribute\EightyfourAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Provider extends EightyfourAttribute
{
    public function __construct(
        private readonly bool $enabled = false,
        private readonly array $providers = [],
        private readonly ?string $default = null,
        private readonly ?string $exceptionType = null,
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getProviders(): array
    {
        return $this->providers;
    }

    public function getDefault(): ?string
    {
        return $this->default;
    }

    public function getExceptionType(): ?string
    {
        return $this->exceptionType;
    }
}