<?php

namespace EightyfourTests\Core\Request;

use Eightyfour\Core\Request\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Request::class)]
class RequestTest extends TestCase
{
    public function testsGetHeaders(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getHeaders();

        // Expects
        $this->assertNull(actual: $result);
    }

    public function testsGetServer(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getServer();

        // Expects
        $this->assertIsArray(actual: $result);
    }

    public function testsGetGet(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getGet();

        // Expects
        $this->assertNull(actual: $result);
    }

    public function testsGetPost(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getPost();

        // Expects
        $this->assertNull(actual: $result);
    }

    public function testsGetFiles(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getFiles();

        // Expects
        $this->assertNull(actual: $result);
    }

    public function testsGetRequest(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getRequest();

        // Expects
        $this->assertNull(actual: $result);
    }

    public function testsGetSession(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getSession();

        // Expects
        $this->assertNull(actual: $result);
    }

    public function testsGetEnv(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getEnv();

        // Expects
        $this->assertIsArray(actual: $result);
    }

    public function testsGetCookie(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class?->getCookie();

        // Expects
        $this->assertNull(actual: $result);
    }

    public function testsGetRequestedUri(): void
    {
        // Given
        $class = $this->init();

        // When
        $result = $class !== null ? $class::getRequestedUri() : '/';

        // Expects
        $this->assertNull(actual: $result);
    }

    private function init(): ?Request
    {
        return Request::createFromGlobals();
    }
}