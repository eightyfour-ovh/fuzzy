<?php

namespace Eightyfour\Fuzzy\Abstract;

use Eightyfour\Fuzzy\Interface\NetworkInterface;

abstract class AbstractNetwork implements NetworkInterface
{
    public function __construct(private ?int $cnx = 2)
    {
    }

    public function connect(): void
    {
        // TODO: Implement connect() method.
    }

    public function communicate(): void
    {
        // TODO: Implement communicate() method.
    }

    public function switch(): void
    {
        // TODO: Implement switch() method.
    }

    public function disconnect(): void
    {
        // TODO: Implement disconnect() method.
    }

    public function getCnx(): ?int
    {
        return $this->cnx;
    }

    public function setCnx(?int $cnx): self
    {
        $this->cnx = $cnx;

        return $this;
    }
}