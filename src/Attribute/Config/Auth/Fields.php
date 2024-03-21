<?php

namespace Eightyfour\Attribute\Config\Auth;

use Attribute;
use Eightyfour\Attribute\EightyfourAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Fields extends EightyfourAttribute
{
    public function __construct(
        private readonly string $login,
        private readonly string $password,
    ) {
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}