<?php

use Bio\Core\ConfiguratorFactory;


require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('UTC');

$configurator = (new ConfiguratorFactory())->create();


return $configurator->createContainer();
