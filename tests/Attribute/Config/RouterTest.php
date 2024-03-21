<?php

namespace EightyfourTests\Attribute\Config;

use Eightyfour\Attribute\Config\Router;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Router::class)]
class RouterTest extends TestCase
{
    public function testsGetDirectories(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getDirectories();

        // Expects
        $this->assertIsArray(actual: $result);
        $this->assertSame(expected: 'Controller', actual: $result[0]);
    }

    private function init(): Router
    {
        return new Router(
            directories: ['Controller']
        );
    }
}