<?php

namespace Eightyfour\Fuzzy\Interface;

interface NeuralNetworkInterface
{
    public function create(NeuralInterface $neuron, NetworkInterface $network): self;

    public function mergeNeurons(NeuralInterface $neu1, NeuralInterface $neu2): self;

    public function mergeNetworks(NetworkInterface $net1, NetworkInterface $net2): self;

    public function merge(self $nn1, self $nn2): self;

    public function start(): self;

    public function parallelism(): self;

    public function run(): self;

    public function feed(): self;

    public function spread(): self;

    public function stop(): self;

    public function destroy(): void;

    public function getNeuralCollection(): ?array;

    public function addNeuron(NeuralInterface $neuron): self;

    public function getNetworkCollection(): ?array;

    public function addNetwork(NetworkInterface $network): self;

    public function getNeuron(): NeuralInterface;

    public function setNeuron(NeuralInterface $neuron): self;

    public function getNetwork(): NetworkInterface;

    public function setNetwork(NetworkInterface $network): self;
}