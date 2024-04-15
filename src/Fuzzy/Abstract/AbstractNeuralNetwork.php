<?php

namespace Eightyfour\Fuzzy\Abstract;

use Eightyfour\Collections\Collection;
use Eightyfour\Fuzzy\Interface\NetworkInterface;
use Eightyfour\Fuzzy\Interface\NeuralInterface;
use Eightyfour\Fuzzy\Interface\NeuralNetworkInterface as Nni;

abstract class AbstractNeuralNetwork implements Nni
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

    public function create(NeuralInterface $neuron, NetworkInterface $network): Nni
    {
        return $this
            ->setNeuron(neuron: $neuron)
            ->setNetwork(network: $network)
        ;
    }

    public function mergeNeurons(NeuralInterface $neu1, NeuralInterface $neu2): Nni
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

    public function mergeNetworks(NetworkInterface $net1, NetworkInterface $net2): Nni
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

    public function merge(Nni $nn1, Nni $nn2): Nni
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

    public function start(): Nni
    {
        // TODO: Implement start() method.

        return $this;
    }

    public function parallelism(): Nni
    {
        // TODO: Implement parallelism() method.

        return $this;
    }

    public function run(): Nni
    {
        // TODO: Implement run() method.

        return $this;
    }

    public function feed(): Nni
    {
        // TODO: Implement feed() method.

        return $this;
    }

    public function spread(): Nni
    {
        // TODO: Implement spread() method.

        return $this;
    }

    public function stop(): Nni
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

    public function addNeuron(NeuralInterface $neuron): Nni
    {
        $this->neurons?->add(element: $neuron);

        return $this;
    }

    public function getNetworkCollection(): ?array
    {
        return $this->networks?->getArrayCopy();
    }

    public function addNetwork(NetworkInterface $network): Nni
    {
        $this->networks?->add(element: $network);

        return $this;
    }

    public function getNeuron(): NeuralInterface
    {
        return $this->neuron;
    }

    public function setNeuron(NeuralInterface $neuron): Nni
    {
        $this->neuron = $neuron;

        return $this;
    }

    public function getNetwork(): NetworkInterface
    {
        return $this->network;
    }

    public function setNetwork(NetworkInterface $network): Nni
    {
        $this->network = $network;

        return $this;
    }
}