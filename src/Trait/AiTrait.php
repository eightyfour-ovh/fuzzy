<?php

namespace Eightyfour\Trait;

use Eightyfour\Core\Ai;
use Eightyfour\Core\Fuzzy;

trait AiTrait
{
    public function newAi(): Ai
    {
        return Fuzzy::newInstance();
    }
}