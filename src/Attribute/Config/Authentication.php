<?php

namespace Eightyfour\Attribute\Config;

use Attribute;
use Eightyfour\Attribute\Config\Auth\Authenticator;
use Eightyfour\Attribute\Config\Auth\Provider;
use Eightyfour\Attribute\EightyfourAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Authentication extends EightyfourAttribute
{
    public function __construct(
        private readonly bool $enabled = false,
        private readonly ?Provider $provider = null,
        private readonly ?Authenticator $authenticator = null,
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function getAuthenticator(): ?Authenticator
    {
        return $this->authenticator;
    }
}