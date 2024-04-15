<?php

namespace Eightyfour\Fuzzy\Type;

use Eightyfour\Fuzzy\Enum\NeuralTypesEnum;
use Eightyfour\Fuzzy\Interface\NeuralTypeInterface;
use Eightyfour\Fuzzy\Neuron;
use Override;

class StringNeuralType extends Neuron implements NeuralTypeInterface
{
    private const NeuralTypesEnum TYPE = NeuralTypesEnum::String;

    public function __construct()
    {
        $enum = self::TYPE;
        parent::__construct(type: $enum->value);
    }

    #[Override] public function analyze(mixed $data): self
    {
        // TODO: Implement analyze() method.

        return $this;
    }

    #[Override] public function filter(mixed $data, ?array $filter = null): self
    {
        // TODO: Implement filter() method.

        return $this;
    }

    #[Override] public function compare(mixed $a, mixed $b): self
    {
        // TODO: Implement compare() method.

        return $this;
    }

    #[Override] public function assume(mixed $c): self
    {
        // TODO: Implement assume() method.

        return $this;
    }

    #[Override] public function getType(): NeuralTypesEnum
    {
        return self::TYPE;
    }
}