<?php

namespace EightyfourTests\Benchmark;

use Eightyfour\Configuration\Configurator;
use Eightyfour\Core\Core;
use Eightyfour\Core\DotEnv;
use Eightyfour\Core\Kernel;
use Eightyfour\Core\Request\Request;
use Eightyfour\Router\Router;
use Eightyfour\Security\System;
use PhpBench\Attributes as Bench;

class KernelBench
{
    #[Bench\Revs(revs: 1000)]
    #[Bench\Iterations(iterations: 50)]
    #[Bench\Assert('mode(variant.time.avg) < 10 ms')]
    #[Bench\OutputTimeUnit(timeUnit: 'milliseconds', precision: 5)]
    #[Bench\OutputMode('throughput')]
    #[Bench\Groups(groups: ['index.php', 'kernel'])]
    public function benchBootHandleAndTerminate(): void
    {
        // Pre-launcher for benchmarking
        $core = Core::boot();
        $config = Configurator::init(core: $core);
        $router = Router::init(core: $core, config: $config);
        $system = System::launch(
            core: $core,
            config: $config,
            router: $router,
            path: '/_fuzzy_tests'
        );

        // Start the Kernel as a StdClass to inject a Route
        $kernel = new Kernel(
            env: DotEnv::isProd() ? 'prod' : 'dev',
            debug: !DotEnv::isProd(),
            core: $core,
            conf: $config,
            router: $router,
            system: $system
        );

        // Run the framework like `index.php`
        $kernel->handle(request: Request::createFromGlobals())->terminate();
    }
}
