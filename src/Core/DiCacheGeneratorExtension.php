<?php declare(strict_types=1);

namespace Bio\Core;

use Contributte\Console\Extra\Cache\Generators\DiContainersCacheGenerator;
use Contributte\Console\Extra\Command\AdvancedCache\CacheGenerateCommand;
use Nette\DI\CompilerExtension;


class DiCacheGeneratorExtension extends CompilerExtension {

    public function loadConfiguration(): void {
        $containerBuilder = $this->getContainerBuilder();

        if (!$containerBuilder->parameters['consoleMode']) {
            return;
        }

        $generator = $containerBuilder->addDefinition($this->prefix('di.generator'))
                                      ->setType(DiContainersCacheGenerator::class)
                                      ->setArguments(
                                          [
                                              [
                                                  'production' => ['debugMode' => false, 'consoleMode' => false, 'wwwDir' => \dirname(__DIR__, 2) . '/www'],
                                                  'console' => ['debugMode' => false, 'consoleMode' => true],
                                              ],
                                              (new ConfiguratorFactory())->create(),
                                          ]
                                      );

        $containerBuilder->addDefinition($this->prefix('generatorCommand'))
                         ->setType(CacheGenerateCommand::class)
                         ->setArguments([[$generator]]);
    }

}
