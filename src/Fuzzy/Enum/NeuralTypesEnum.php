<?php

namespace Eightyfour\Fuzzy\Enum;

use Eightyfour\Fuzzy\Interface\NeuralTypeInterface;
use Eightyfour\Fuzzy\Type\StringNeuralType;

enum NeuralTypesEnum: string
{
    case String = StringNeuralType::class;

    public function isValid(): bool
    {
        return new $this->value instanceof NeuralTypeInterface;
    }
}
