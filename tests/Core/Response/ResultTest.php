<?php

namespace EightyfourTests\Core\Response;

use Eightyfour\Core\Response\Result;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Result::class)]
class ResultTest extends TestCase
{
    public function testsGetData(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getData();

        // Expects
        $this->assertIsArray(actual: $result);
        $this->assertSame(expected: 'OK', actual: $result['result']);
    }

    public function testsGetFormat(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getFormat();

        // Expects
        $this->assertSame(expected: 'json', actual: $result);
    }

    private function init(): Result
    {
        return new Result(
            data: ['result' => 'OK'],
            format: 'json'
        );
    }
}