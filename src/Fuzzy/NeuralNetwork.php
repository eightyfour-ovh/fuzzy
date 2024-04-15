<?php

namespace Eightyfour\Fuzzy;

use Eightyfour\Collections\Collection;
use Eightyfour\Fuzzy\Abstract\AbstractNeuralNetwork;
use Eightyfour\Fuzzy\Interface\NetworkInterface;
use Eightyfour\Fuzzy\Interface\NeuralInterface;
use Eightyfour\Fuzzy\Interface\NeuralNetworkInterface as Nni;
use Override;

class NeuralNetwork extends AbstractNeuralNetwork
{
    public function __construct(
        private readonly ?Collection $neurons = null,
        private readonly ?Collection $networks = null
    ) {
        parent::__construct(neurons: $this->neurons, networks: $this->networks);
    }

    #[Override] public function create(NeuralInterface $neuron, NetworkInterface $network): Nni
    {
        return parent::create(neuron: $neuron, network: $network);
    }

    #[Override] public function mergeNeurons(NeuralInterface $neu1, NeuralInterface $neu2): Nni
    {
        return parent::mergeNeurons(neu1: $neu1, neu2: $neu2);
    }

    #[Override] public function mergeNetworks(NetworkInterface $net1, NetworkInterface $net2): Nni
    {
        return parent::mergeNetworks(net1: $net1, net2: $net2);
    }

    #[Override] public function merge(Nni $nn1, Nni $nn2): Nni
    {
        return parent::merge(nn1: $nn1, nn2: $nn2);
    }

    #[Override] public function start(): Nni
    {
        // TODO: Implement start() method.

        return parent::start();
    }

    #[Override] public function parallelism(): Nni
    {
        // TODO: Implement parallelism() method.

        return parent::parallelism();
    }

    #[Override] public function run(): Nni
    {
        // TODO: Implement run() method.

        return parent::run();
    }

    #[Override] public function feed(): Nni
    {
        // TODO: Implement feed() method.

        return parent::feed();
    }

    #[Override] public function spread(): Nni
    {
        // TODO: Implement spread() method.

        return parent::spread();
    }

    #[Override] public function stop(): Nni
    {
        // TODO: Implement stop() method.

        return parent::stop();
    }

    #[Override] public function destroy(): void
    {
        // TODO: Implement destroy() method.
        parent::destroy();
    }

    #[Override] public function getNeuralCollection(): ?array
    {
        return parent::getNeuralCollection();
    }

    #[Override] public function addNeuron(NeuralInterface $neuron): Nni
    {
        return parent::addNeuron(neuron: $neuron);
    }

    #[Override] public function getNetworkCollection(): ?array
    {
        return parent::getNetworkCollection();
    }

    #[Override] public function addNetwork(NetworkInterface $network): Nni
    {
        return parent::addNetwork(network: $network);
    }

    #[Override] public function getNeuron(): NeuralInterface
    {
        return parent::getNeuron();
    }

    #[Override] public function setNeuron(NeuralInterface $neuron): Nni
    {
        return parent::setNeuron(neuron: $neuron);
    }

    #[Override] public function getNetwork(): NetworkInterface
    {
        return parent::getNetwork();
    }

    #[Override] public function setNetwork(NetworkInterface $network): Nni
    {
        return parent::setNetwork(network: $network);
    }
}