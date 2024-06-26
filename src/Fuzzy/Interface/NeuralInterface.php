<?php

namespace Eightyfour\Fuzzy\Interface;

use Eightyfour\Fuzzy\Enum\NeuralTypesEnum;

interface NeuralInterface extends NeuralTypeInterface
{
    public function analyze(mixed $data): self;

    public function filter(mixed $data, ?array $filter = null): self;

    public function compare(mixed $a, mixed $b): self;

    public function assume(mixed $c): self;

    public function getType(): NeuralTypesEnum;

    public function retrieve(): self;

    public function result(): self;

    public function setType(?string $type): ?NeuralTypesEnum;
}