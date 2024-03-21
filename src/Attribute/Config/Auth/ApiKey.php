<?php

namespace Eightyfour\Attribute\Config\Auth;

use Attribute;
use Eightyfour\Attribute\EightyfourAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ApiKey extends EightyfourAttribute
{
    public function __construct(
        private readonly string $key,
        private readonly string $secret,
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }
}