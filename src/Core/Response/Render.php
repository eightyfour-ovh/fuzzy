<?php

namespace Eightyfour\Core\Response;

use Eightyfour\Core\Reflection\ControllerLauncher;

class Render
{
    public function __construct(
        private readonly \ReflectionClass $class,
        private readonly string $method,
        private readonly ?\ReflectionMethod $constructor = null,
        private readonly ?array $constructorParams = null,
        private readonly ?\ReflectionClass $parentClass = null,
        private readonly ?\ReflectionMethod $parentConstructor = null,
        private readonly ?array $parentConstructorParams = null,
    ) {
    }

    public static function init(ControllerLauncher $launcher): self
    {
        return new self(
            class: $launcher->getClass(),
            method: $launcher->getMethod(),
            constructor: $launcher->getConstructor(),
            constructorParams: $launcher->getConstructorParams(),
            parentClass: $launcher->getParentClass(),
            parentConstructor: $launcher->getParentConstructor(),
            parentConstructorParams: $launcher->getParentConstructorParams()
        );
    }

    public function getClass(): \ReflectionClass
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParentClass(): ?\ReflectionClass
    {
        return $this->parentClass;
    }

    public function getConstructor(): ?\ReflectionMethod
    {
        return $this->constructor;
    }

    public function getConstructorParams(): ?array
    {
        return $this->constructorParams;
    }

    public function getParentConstructor(): ?\ReflectionMethod
    {
        return $this->parentConstructor;
    }

    public function getParentConstructorParams(): ?array
    {
        return $this->parentConstructorParams;
    }
}