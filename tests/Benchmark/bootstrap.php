<?php

use Eightyfour\Core\DotEnv;

require dirname(__DIR__).'/../vendor/autoload.php';

const __PROJECT__ = __DIR__ . '/../../';
const __APP__ = __DIR__ . '/../MockedApp';


(new DotEnv())->load(path: __PROJECT__ . '.env.test');

