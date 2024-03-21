<?php

namespace Eightyfour\Router;

use Eightyfour\Abstract\AbstractController;
use Eightyfour\Attribute\Router\Route as AttrRoute;
use Eightyfour\Configuration\Configurator;
use Eightyfour\Core\Core;
use Eightyfour\Core\Reflection\AttributesReader as Attr;
use Eightyfour\Core\Reflection\ControllerLauncher;
use Eightyfour\Core\Request\Request;
use Eightyfour\Core\Response\Render;

class Router
{
    public const string CURRENT_CLASSNAME = 'className';
    public const string CURRENT_METHOD = 'method';
    public const string DEFAULT_CONTROLLER = AbstractController::class;
    public const string DEFAULT_METHOD = 'default';

    public function __construct(
        private readonly ?Core $core = null,
        private readonly ?Configurator $config = null,
        private Routes $routes = new Routes()
    ) {
    }

    public static function init(?Core $core = null, ?Configurator $config = null): ?self
    {
        $loader = new Loader();
        $router = new self(
            core: $core,
            config: $config,
            routes: new Routes()
        );
        $routes = new Routes();

        $filesToScan = $loader->scan(config: $router->config);

        foreach ($filesToScan as $file) {
            $classRouting = Attr::readClass(file: $router->core?->getPath(path: $file));
            if ($classRouting) {
                $routes = $router->convertToRoutes(
                    classRouting: $classRouting,
                    routes: $routes
                );
            }
        }
        $router->setRoutes(routes: $routes);

        return $router;
    }

    public function getRoutes(): array
    {
        return $this->routes->getArrayCopy();
    }

    public function getRoute(?string $name = null, ?string $path = null): ?Route
    {
        if ($name !== null || $path !== null) {
            /** @var Route $route */
            foreach ($this->getRoutes() as $route) {
                if ($name !== null && $path !== null) {
                    if ($route->getName() === $name && $route->getPath() === $path) {
                        return $route;
                    }
                } else if ($name !== null && $path === null) {
                    if ($route->getName() === $name) {
                        return $route;
                    }
                } else if ($name === null && $path !== null) {
                    if ($route->getPath() === $path) {
                        return $route;
                    }
                }
            }
        }

        return null;
    }

    public function getRendering(?Request $request = null, ?Route $current = null): Render
    {
        $launcher = ControllerLauncher::init(route: $current)
            ->prepareConstructor()
            ->prepareDependencies()
            ->matchParameters(request: $request)
        ;
        // TODO: handle the rest of this

        return Render::init(launcher: $launcher);
    }

    private function setRoutes(Routes $routes): self
    {
        $this->routes = $routes;

        return $this;
    }

    private function convertToRoutes(array $classRouting, Routes $routes): Routes
    {
        foreach ($classRouting[AttrRoute::class][Attr::MTD] as $method => $value) {
            $argPath = !empty($classRouting[AttrRoute::class][Attr::ARG][Attr::PATH]) ?
                $classRouting[AttrRoute::class][Attr::ARG][Attr::PATH] : null;
            $argName = !empty($classRouting[AttrRoute::class][Attr::ARG][Attr::NAME]) ?
                $classRouting[AttrRoute::class][Attr::ARG][Attr::NAME] : null;
            $argMethods = !empty($classRouting[AttrRoute::class][Attr::ARG][Attr::METHODS]) ?
                $classRouting[AttrRoute::class][Attr::ARG][Attr::METHODS] : [];
            $mtdPath = !empty($classRouting[AttrRoute::class][Attr::MTD][$method][Attr::ARG][Attr::PATH]) ?
                $classRouting[AttrRoute::class][Attr::MTD][$method][Attr::ARG][Attr::PATH] : null;
            $mtdName = !empty($classRouting[AttrRoute::class][Attr::MTD][$method][Attr::ARG][Attr::NAME]) ?
                $classRouting[AttrRoute::class][Attr::MTD][$method][Attr::ARG][Attr::NAME] : null;
            $mtdMethods = !empty($classRouting[AttrRoute::class][Attr::MTD][$method][Attr::ARG][Attr::METHODS]) ?
                $classRouting[AttrRoute::class][Attr::MTD][$method][Attr::ARG][Attr::METHODS] : [];
            $route = new Route(
                path: $argPath . $mtdPath,
                name: $argName . $mtdName,
                methods: $this->core?->merge(array: $argMethods, extras: $mtdMethods) ?: [],
                controller: [
                    self::CURRENT_CLASSNAME => $classRouting['className'],
                    self::CURRENT_METHOD => $method
                ]
            );
            $routes->add(element: $route);
        }

        return $routes;
    }
}