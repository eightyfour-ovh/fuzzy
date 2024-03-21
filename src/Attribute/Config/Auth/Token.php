<?php

namespace Eightyfour\Attribute\Config\Auth;

use Attribute;
use Eightyfour\Attribute\EightyfourAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Token extends EightyfourAttribute
{
    public function __construct(
        private readonly string $token,
    ) {
    }

    public function getToken(): string
    {
        return $this->token;
    }
}