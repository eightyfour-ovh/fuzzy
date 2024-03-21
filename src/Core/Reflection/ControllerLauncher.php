<?php

namespace Eightyfour\Core\Reflection;

use Eightyfour\Abstract\AbstractController;
use Eightyfour\Core\Request\Request;
use Eightyfour\Core\Response\Render;
use Eightyfour\Exception\Exception;
use Eightyfour\Router\Route;
use Eightyfour\Router\Router;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionType;

class ControllerLauncher
{
    private \ReflectionClass $class;
    private ?\ReflectionMethod $constructor;
    private ?array $constructorParams;
    private ?\ReflectionClass $parentClass;
    private ?\ReflectionMethod $parentConstructor;
    private ?array $parentConstructorParams;
    private array $methodParams = [];

    public function __construct(
        private readonly string $className,
        private readonly string $method
    ) {
    }

    public static function init(?Route $route = null): self
    {
        if ($route === null) {
            // TODO: handle this
            throw new Exception(message: 'no route found.');
        }

        $controller = $route->getController() ?: null;
        $className = $controller ? $controller[Router::CURRENT_CLASSNAME] : AbstractController::class;

        $self = new self(
            className: $className,
            method: $controller ? $controller[Router::CURRENT_METHOD] : Router::DEFAULT_METHOD
        );

        return $self->setClass(className: $className);
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getClass(): \ReflectionClass
    {
        return $this->class;
    }

    public function getConstructor(): ?\ReflectionMethod
    {
        return $this->constructor;
    }

    public function getConstructorParams(): ?array
    {
        return $this->constructorParams;
    }

    public function getParentClass(): ?\ReflectionClass
    {
        return $this->parentClass;
    }

    public function getParentConstructor(): ?\ReflectionMethod
    {
        return $this->parentConstructor;
    }

    public function getParentConstructorParams(): ?array
    {
        return $this->parentConstructorParams;
    }

    public function getMethodParams(): array
    {
        return $this->methodParams;
    }

    /**
     * @param class-string $className
     * @throws ReflectionException
     */
    private function setClass(string $className): ControllerLauncher
    {
        $this->class = new ReflectionClass(objectOrClass: $className);

        return $this;
    }

    public function prepareConstructor(): self
    {
        $this->constructor = $this->class->getConstructor();
        $this->constructorParams = $this->constructor?->getParameters();
        $this->parentClass = $this->class->getParentClass() ?: null;
        $this->parentConstructor = $this->parentClass?->getConstructor();
        $this->parentConstructorParams = $this->parentConstructor?->getParameters();

        return $this;
    }

    public function prepareDependencies(): self
    {
        try {
            if (method_exists(object_or_class: $this->getClassName(), method: $this->getMethod())) {
                $method = $this->class->getMethod(name: $this->getMethod());
                $params = $method->getParameters();
                if (count(value: $params) > 0) {
                    foreach ($params as $param) {
                        $this->methodParams[$param->getName()] = [
                            'type' => $param->getType(),
                            'nullable' => $param->getType()?->allowsNull(),
                            'optional' => $param->isOptional(),
                        ];
                    }
                }
            }
        } catch (ReflectionException $e) {
            throw new $e;
        }

        return $this;
    }

    public function matchParameters(?Request $request = null): self
    {
        // TODO: handle this method.

        return $this;
    }

    /**
     * @param class-string $className
     * @throws ReflectionException
     */
    public static function handleDependencies(string $className): ?object
    {
        $objMapper[$className] = [];
        try {
            $class = new ReflectionClass(objectOrClass: $className);
            $obj = null;

            if (!$class->isAbstract()){
                $constructor = $class->getConstructor();
                $params = $constructor?->getParameters();
                if ($params !== null) {
                    foreach ($params as $param) {
                        $paramClass = null;
                        $paramType = $param->getType();
                        if ($paramType instanceof ReflectionNamedType) {
                            if (!$paramType->isBuiltin()) {
                                /** @var class-string $paramClass */
                                $paramClass = $paramType->getName();
                                if ($paramClass !== null) {
                                    $paramClass = self::handleDependencies(className: $paramClass);
                                    $objMapper[$className][] = $paramClass;
                                }
                            } else {
                                $objMapper[$className][] = !$paramType->allowsNull() ? $paramType->getName() : null;
                            }
                        }
                    }
                }
                $obj = $class->newInstanceArgs(args: $objMapper[$className]);
            }
        } catch (ReflectionException $e) {
            throw new $e;
        }

        return $obj;
    }
}