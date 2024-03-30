<?php

namespace EightyfourTests\Benchmark;

use Eightyfour\Configuration\Configurator;
use Eightyfour\Core\Console\Application;
use Eightyfour\Core\Core;
use Eightyfour\Core\DotEnv;
use Eightyfour\Core\Kernel;
use Eightyfour\Core\Request\Request;
use Eightyfour\Router\Router;
use Eightyfour\Security\System;
use PhpBench\Attributes as Bench;

class CliBench
{
    #[Bench\Revs(revs: 100)]
    #[Bench\Iterations(iterations: 5)]
    #[Bench\Assert('mode(variant.time.avg) < 10 ms')]
    #[Bench\OutputTimeUnit(timeUnit: 'milliseconds', precision: 5)]
    #[Bench\OutputMode('throughput')]
    #[Bench\Groups(groups: ['index.php', 'cli'])]
    public function benchBootAndRun(): void
    {
        // Pre-launcher for benchmarking
        $core = Core::boot();
        $config = Configurator::init(core: $core);
        $router = Router::init(core: $core, config: $config);
        $system = System::launch(
            core: $core,
            config: $config,
            router: $router
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
        (new Application(args: ['bin/console'], kernel: $kernel))->run();
    }
}
