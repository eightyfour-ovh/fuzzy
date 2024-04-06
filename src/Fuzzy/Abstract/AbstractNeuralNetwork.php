<?php

namespace Eightyfour\Fuzzy\Abstract;

use Eightyfour\Collections\Collection;
use Eightyfour\Fuzzy\Interface\NetworkInterface;
use Eightyfour\Fuzzy\Interface\NeuralInterface;
use Eightyfour\Fuzzy\Interface\NeuralNetworkInterface;

abstract class AbstractNeuralNetwork implements NeuralNetworkInterface
{
    private NeuralInterface $neuron;
    private NetworkInterface $network;

    public function __construct(
        private ?Collection $neurons = null,
        private ?Collection $networks = null
    ) {
        $this->neurons = new Collection(array: []);
        $this->networks = new Collection(array: []);
    }

    public function create(NeuralInterface $neuron, NetworkInterface $network): self
    {
        return $this
            ->setNeuron(neuron: $neuron)
            ->setNetwork(network: $network)
        ;
    }

    public function mergeNeurons(NeuralInterface $neu1, NeuralInterface $neu2): self
    {
        if ($this->neurons === null) {
            $this->neurons = new Collection(array: []);
        }
        if (empty($this->neurons->getArrayCopy()) && $this->getNeuron() !== null) {
            $this->neurons->add(element: $this->getNeuron());
        }
        $this->neurons->add(element: $neu1);
        $this->neurons->add(element: $neu2);

        return $this;
    }

    public function mergeNetworks(NetworkInterface $net1, NetworkInterface $net2): self
    {
        if ($this->networks === null) {
            $this->networks = new Collection(array: []);
        }
        if (empty($this->networks->getArrayCopy()) && $this->getNetwork() !== null) {
            $this->networks->add(element: $this->getNetwork());
        }
        $this->networks->add(element: $net1);
        $this->networks->add(element: $net2);

        return $this;
    }

    public function merge(NeuralNetworkInterface $nn1, NeuralNetworkInterface $nn2): self
    {
        return $this
            ->mergeNeurons(
                neu1: $nn1->getNeuron(),
                neu2: $nn2->getNeuron()
            )
            ->mergeNetworks(
                net1: $nn1->getNetwork(),
                net2: $nn2->getNetwork()
            )
        ;
    }

    public function start(): self
    {
        // TODO: Implement start() method.

        return $this;
    }

    public function parallelism(): self
    {
        // TODO: Implement parallelism() method.

        return $this;
    }

    public function run(): self
    {
        // TODO: Implement run() method.

        return $this;
    }

    public function feed(): self
    {
        // TODO: Implement feed() method.

        return $this;
    }

    public function spread(): self
    {
        // TODO: Implement spread() method.

        return $this;
    }

    public function stop(): self
    {
        // TODO: Implement stop() method.

        return $this;
    }

    public function destroy(): void
    {
        // TODO: Implement destroy() method.
    }

    public function getNeuralCollection(): ?array
    {
        return $this->neurons?->getArrayCopy();
    }

    public function addNeuron(NeuralInterface $neuron): self
    {
        $this->neurons?->add(element: $neuron);

        return $this;
    }

    public function getNetworkCollection(): ?array
    {
        return $this->networks?->getArrayCopy();
    }

    public function addNetwork(NetworkInterface $network): self
    {
        $this->networks?->add(element: $network);

        return $this;
    }

    public function getNeuron(): NeuralInterface
    {
        return $this->neuron;
    }

    public function setNeuron(NeuralInterface $neuron): self
    {
        $this->neuron = $neuron;

        return $this;
    }

    public function getNetwork(): NetworkInterface
    {
        return $this->network;
    }

    public function setNetwork(NetworkInterface $network): self
    {
        $this->network = $network;

        return $this;
    }
}