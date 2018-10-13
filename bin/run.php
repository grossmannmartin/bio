#!/usr/bin/env php
<?php

/** @var Nette\DI\Container $container */
$container = require __DIR__ . '/../src/bootstrap.php';

$application = $container->getByType(Contributte\Console\Application::class);

exit($application->run());
