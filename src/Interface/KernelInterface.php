<?php

namespace Eightyfour\Interface;

use Eightyfour\Core\Kernel;
use Eightyfour\Core\Request\Request;

interface KernelInterface
{
    public static function boot(bool $prod = true): ?Kernel;

    public function handle(Request $request): ?Kernel;

    public function terminate(): void;
}