<?php

namespace Eightyfour\Fuzzy\Interface;

interface NetworkInterface
{
    public function connect(): void;

    public function communicate(): void;

    public function switch(): void;

    public function disconnect(): void;
}