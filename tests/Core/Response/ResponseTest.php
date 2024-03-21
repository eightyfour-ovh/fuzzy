<?php

namespace EightyfourTests\Core\Response;

use Eightyfour\Core\Response\Render;
use Eightyfour\Core\Response\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Response::class)]
class ResponseTest extends TestCase
{
    public function testsSetAndGetRender(): void
    {
        // Given
        $class = $this->init();
        $render = $this->createMock(originalClassName: Render::class);

        // When
        $class->setRender(render: $render);

        // Expects
        $this->assertInstanceOf(expected: Render::class, actual: $class->getRender());
    }

    private function init(): Response
    {
        return Response::newInstance();
    }
}