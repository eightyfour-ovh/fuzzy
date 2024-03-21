<?php

namespace Eightyfour\Collections;

use ArrayObject;

class Collection extends ArrayObject
{
    public function add(mixed $element): void
    {
        parent::append(value: $element);
    }
}