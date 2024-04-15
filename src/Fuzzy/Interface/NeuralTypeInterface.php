<?php

namespace Eightyfour\Fuzzy\Interface;

use Eightyfour\Fuzzy\Enum\NeuralTypesEnum;

interface NeuralTypeInterface
{
    public function analyze(mixed $data): self;

    public function filter(mixed $data, ?array $filter = null): self;

    public function compare(mixed $a, mixed $b): self;

    public function assume(mixed $c): self;

    public function getType(): NeuralTypesEnum;
}