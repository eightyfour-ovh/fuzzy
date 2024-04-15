<?php

namespace Eightyfour\Fuzzy;

use Eightyfour\Fuzzy\Abstract\AbstractNeuron;
use Eightyfour\Fuzzy\Enum\NeuralTypesEnum;
use Eightyfour\Fuzzy\Interface\NeuralInterface;
use Eightyfour\Fuzzy\Interface\NeuralTypeInterface;
use Override;

class Neuron extends AbstractNeuron
{
    private NeuralTypesEnum $enum;

    protected readonly ?NeuralTypeInterface $instance;

    public function __construct(private readonly ?string $type = null)
    {
        $enum = $this->setType(type: $this->type);
        $this->instance = $enum !== null ? new $enum->value : null;
    }

    #[Override] public function analyze(mixed $data): NeuralInterface
    {
        // TODO: Implement analyze() method.

        return parent::analyze(data: $data);
    }

    #[Override] public function filter(mixed $data, ?array $filter = null): NeuralInterface
    {
        // TODO: Implement filter() method.

        return parent::filter(data: $data, filter: $filter);
    }

    #[Override] public function compare(mixed $a, mixed $b): NeuralInterface
    {
        // TODO: Implement compare() method.

        return parent::compare(a: $a, b: $b);
    }

    #[Override] public function assume(mixed $c): NeuralInterface
    {
        // TODO: Implement assume() method.

        return parent::assume(c: $c);
    }

    public function getType(): NeuralTypesEnum
    {
        return $this->enum;
    }

    #[Override] public function retrieve(): NeuralInterface
    {
        // TODO: Implement retrieve() method.

        return parent::retrieve();
    }

    #[Override] public function result(): NeuralInterface
    {
        // TODO: Implement result() method.

        return parent::result();
    }

    public function setType(?string $type = null): ?NeuralTypesEnum
    {
        if ($type !== null) {
            $item = NeuralTypesEnum::from(value: $type);
            if ($item->isValid()) {
                $this->enum = $item;

                return $item;
            }
        }

        return null;
    }
}