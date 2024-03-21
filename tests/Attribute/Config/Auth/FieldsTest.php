<?php

namespace EightyfourTests\Attribute\Config\Auth;

use Eightyfour\Attribute\Config\Auth\Fields;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Fields::class)]
class FieldsTest extends TestCase
{
    public function testsGetLogin(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getLogin();

        // Expects
        $this->assertSame(expected: "email", actual: $result);
    }
    public function testsGetPassword(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class->getPassword();

        // Expects
        $this->assertSame(expected: "password", actual: $result);
    }

    private function init(): Fields
    {
        return new Fields(
            login: "email",
            password: "password"
        );
    }
}