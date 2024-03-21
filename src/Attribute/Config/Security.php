<?php

namespace Eightyfour\Attribute\Config;

use Attribute;
use Eightyfour\Attribute\EightyfourAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Security extends EightyfourAttribute
{
    public function __construct(
        private readonly bool $enabled = false,
        private readonly ?Authentication $auth = null,
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getAuth(): ?Authentication
    {
        return $this->auth;
    }
}