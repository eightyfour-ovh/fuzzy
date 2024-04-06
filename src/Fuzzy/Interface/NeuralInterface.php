<?php

namespace Eightyfour\Fuzzy\Interface;

interface NeuralInterface
{
    public function retrieve(): void;

    public function analyze(): void;

    public function filter(): void;

    public function assume(): void;

    public function compare(): void;

    public function result(): void;
}