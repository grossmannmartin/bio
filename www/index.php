<?php

/** @var Nette\DI\Container $container */
$container = require __DIR__ . '/../src/bootstrap.php';

$application = $container->getByType(\Contributte\Middlewares\Application\IApplication::class);
$application->run();
