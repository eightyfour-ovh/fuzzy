<?php

namespace EightyfourTests\Trait;

use Eightyfour\Core\Core;
use Eightyfour\Trait\UtilsTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

class UtilsTraitTest extends TestCase
{
    public function testsGetPath(): void
    {
        // Given
        $trait = $this->init();

        // When
        $result = $trait->getPath(path: 'tests/');

        // Expects
        $this->assertSame(expected: realpath(path: __PROJECT__ . 'tests'), actual: $result);
    }

    public function testsMerge(): void
    {
        // Given
        $trait = $this->init();

        // When
        $result = $trait->merge(array: [ 'key1' => 'value1' ]);

        // Expects
        $this->assertSame(expected: [ 'key1' => 'value1' ], actual: $result);
    }

    public function testsMergeWithExtras(): void
    {
        // Given
        $trait = $this->init();

        // When
        $result = $trait->merge(array: [ 'key1' => 'value1' ], extras: [ 'key2' => 'value2' ]);

        // Expects
        $this->assertSame(expected: [
            'key1' => 'value1',
            'key2' => 'value2',
        ], actual: $result);
    }

    private function init(): Core
    {
        return new Core();
    }
}