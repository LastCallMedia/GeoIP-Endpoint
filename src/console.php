<?php


set_time_limit(0);

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ .'/app.php';
$console = $app['console'];
$console->run();