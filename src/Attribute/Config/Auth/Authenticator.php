<?php

namespace Eightyfour\Attribute\Config\Auth;

use Attribute;
use Eightyfour\Attribute\EightyfourAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Authenticator extends EightyfourAttribute
{
    public function __construct(
        private readonly bool $enabled = false,
        private readonly array $authenticators = [],
        private readonly ?string $default = null,
        private readonly ?Fields $fields = null,
        private readonly ?string $exceptionType = null,
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getAuthenticators(): array
    {
        return $this->authenticators;
    }

    public function getDefault(): ?string
    {
        return $this->default;
    }

    public function getFields(): ?Fields
    {
        return $this->fields;
    }

    public function getExceptionType(): ?string
    {
        return $this->exceptionType;
    }
}