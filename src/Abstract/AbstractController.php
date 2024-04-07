<?php

namespace Eightyfour\Abstract;

use Eightyfour\Core\Fuzzy;

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