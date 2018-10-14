<?php declare(strict_types=1);

namespace Bio\Core\Doctrine;

use Doctrine\Common\Cache\VoidCache;
use Doctrine\ORM\Configuration;
use Nette\DI\CompilerExtension;


class DoctrineDebugVoidCacheExtension extends CompilerExtension {

    public function beforeCompile(): void {
        $containerBuilder = $this->getContainerBuilder();

        if (!$containerBuilder->parameters['debugMode']) {
            return;
        }

        $configuration = $containerBuilder->getDefinitionByType(Configuration::class);

        $voidCache = $containerBuilder->addDefinition($this->prefix('voidCache'))
                                      ->setType(VoidCache::class);

        $configuration->addSetup('setQueryCacheImpl', [$voidCache])
                      ->addSetup('setResultCacheImpl', [$voidCache])
                      ->addSetup('setHydrationCacheImpl', [$voidCache])
                      ->addSetup('setMetadataCacheImpl', [$voidCache])
                      ->addSetup('setAutoGenerateProxyClasses', [true]);
    }

}
