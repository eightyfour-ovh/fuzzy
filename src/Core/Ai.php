<?php

namespace Eightyfour\Core;

use Eightyfour\Collections\Collection;
use Eightyfour\Fuzzy\Enum\TypesEnum;
use Eightyfour\Fuzzy\Interface\NetworkInterface;
use Eightyfour\Fuzzy\Interface\NeuralInterface;
use Eightyfour\Fuzzy\Interface\NeuralNetworkInterface;
use Eightyfour\Fuzzy\Network;
use Eightyfour\Fuzzy\NeuralNetwork;
use Eightyfour\Fuzzy\Neuron;

class Ai
{
    public function create(
        TypesEnum $type,
        ?int $instances = 2,
        ?string $nt = null
    ): Collection {
        $creations = new Collection(array: []);
        for ($i = 0; $i < $instances; $i++) {
            $creation = match (TypesEnum::getLabel(item: $type)) {
                TypesEnum::Neuron->value => $this->createNeuron(type: $nt),
                TypesEnum::Network->value => $this->createNetwork(cnx: $instances),
                TypesEnum::NeuralNetwork->value => $this->createNeuralNetwork(instances: $instances, nt: $nt),
                default => $this
            };
            $creations->add(element: $creation);
        }

        return $creations;
    }

    public function aiConnect(
        NeuralNetworkInterface $nn1,
        NeuralNetworkInterface $nn2,
        ?int $instances = 2,
        ?string $nt = null
    ): NeuralNetworkInterface {
        return (new NeuralNetwork())
            ->create(
                neuron: $this->createNeuron(type: $nt),
                network: $this->createNetwork(cnx: $instances)
            )
            ->merge(
                nn1: $nn1,
                nn2: $nn2
            )
            ;
    }

    private function createNeuron(?string $type = null): NeuralInterface
    {
        return new Neuron(type: $type);
    }

    private function createNetwork(?int $cnx = 2): NetworkInterface
    {
        return new Network(cnx: $cnx);
    }

    private function createNeuralNetwork(
        ?NeuralNetworkInterface $neuralNetwork = null,
        ?NeuralInterface $neuron = null,
        ?NetworkInterface $network = null,
        ?int $instances = 2,
        ?string $nt = null
    ): NeuralNetworkInterface {
        $nn = $neuralNetwork ?: new NeuralNetwork();
        $neuron = $neuron ?: $this->createNeuron(type: $nt);
        $network = $network ?: $this->createNetwork(cnx: $instances);

        return $nn->create(
            neuron: $neuron,
            network: $network
        );
    }
}