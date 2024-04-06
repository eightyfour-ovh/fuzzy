<?php

namespace Eightyfour\Abstract;

use Eightyfour\Collections\Collection;
use Eightyfour\Core\Fuzzy;
use Eightyfour\Fuzzy\Enum\TypesEnum;

abstract class AbstractController
{
    public function __construct(
        protected Fuzzy $fuzzy
    ) {
    }

    public function getFuzzy(): Fuzzy
    {
        return $this->fuzzy;
    }
}