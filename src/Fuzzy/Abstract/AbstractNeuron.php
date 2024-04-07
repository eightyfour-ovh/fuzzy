<?php

namespace Eightyfour\Fuzzy\Abstract;

use Eightyfour\Fuzzy\Interface\NeuralInterface;

abstract class AbstractNeuron implements NeuralInterface
{
    public function __construct(private ?string $type = null)
    {
    }

    public function retrieve(): void
    {
        // TODO: Implement retrieve() method.
    }

    public function analyze(): void
    {
        // TODO: Implement analyze() method.
    }

    public function filter(): void
    {
        // TODO: Implement filter() method.
    }

    public function assume(): void
    {
        // TODO: Implement assume() method.
    }

    public function compare(): void
    {
        // TODO: Implement compare() method.
    }

    public function result(): void
    {
        // TODO: Implement result() method.
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }
}