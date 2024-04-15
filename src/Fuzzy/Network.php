<?php

namespace Eightyfour\Fuzzy;

use Eightyfour\Fuzzy\Abstract\AbstractNetwork;
use Override;

class Network extends AbstractNetwork
{
    public function __construct(private readonly ?int $cnx = 2)
    {
        parent::__construct(cnx: $this->cnx);
    }

    #[Override] public function connect(): void
    {
        // TODO: Implement connect() method.
        parent::connect();
    }

    #[Override] public function communicate(): void
    {
        // TODO: Implement communicate() method.
        parent::communicate();
    }

    #[Override] public function switch(): void
    {
        // TODO: Implement switch() method.
        parent::switch();
    }

    #[Override] public function disconnect(): void
    {
        // TODO: Implement disconnect() method.
        parent::disconnect();
    }

    #[Override] public function getCnx(): ?int
    {
        return parent::getCnx();
    }
}