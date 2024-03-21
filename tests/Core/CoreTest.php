<?php

namespace EightyfourTests\Core;

use Eightyfour\Core\Core;
use Eightyfour\Core\DotEnv;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Core::class)]
class CoreTest extends TestCase
{
    public function testsBoot(): void
    {
        // Given
        $class = $this->init();

        // Expects
        $this->assertInstanceOf(expected: Core::class, actual: $class);
    }

    private function init(): ?Core
    {
        return Core::boot();
    }
}