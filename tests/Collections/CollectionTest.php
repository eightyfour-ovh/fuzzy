<?php

namespace EightyfourTests\Collections;

use Eightyfour\Collections\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Collection::class)]
class CollectionTest extends TestCase
{
    public function testsAdd(): void
    {
        // Given
        $class = $this->init();

        // When
        $class->add(element: 'item');

        // Expects
        $this->assertInstanceOf(expected: Collection::class, actual: $class);
        $this->assertSame(expected: 'item', actual: $class->offsetGet(key: 0));
    }

    private function init(): Collection
    {
        return new Collection();
    }
}