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

class Fuzzy
{
    public function aiCreate(TypesEnum $type, ?int $instances = 2): Collection
    {
        $creations = new Collection(array: []);
        for ($i = 0; $i < $instances; $i++) {
            $creation = match (TypesEnum::getLabel(item: $type)) {
                TypesEnum::Neuron->value => $this->createNeuron(),
                TypesEnum::Network->value => $this->createNetwork(),
                TypesEnum::NeuralNetwork->value => $this->createNeuralNetwork(),
                default => $this
            };
            $creations->add(element: $creation);
        }

        return $creations;
    }

    public function aiConnect(NeuralNetworkInterface $nn1, NeuralNetworkInterface $nn2): NeuralNetworkInterface
    {
        return (new NeuralNetwork())
            ->create(
                neuron: $this->createNeuron(),
                network: $this->createNetwork()
            )
            ->merge(
                nn1: $nn1,
                nn2: $nn2
            )
            ;
    }

    private function createNeuron(?string $type = null): NeuralInterface
    {
        $neuron = new Neuron(type: $type);

        return $neuron;
    }

    private function createNetwork(?int $cnx = 2): NetworkInterface
    {
        $network = new Network(cnx: $cnx);

        return $network;
    }

    private function createNeuralNetwork(
        ?NeuralNetworkInterface $neuralNetwork = null,
        ?NeuralInterface $neuron = null,
        ?NetworkInterface $network = null
    ): NeuralNetworkInterface {
        $nn = $neuralNetwork ?: new NeuralNetwork();
        $neuron = $neuron ?: $this->createNeuron();
        $network = $network ?: $this->createNetwork();

        return $nn->create(
            neuron: $neuron,
            network: $network
        );
    }
}