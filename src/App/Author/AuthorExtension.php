<?php declare(strict_types=1);

namespace Bio\App\Author;

use Bio\App\Author\Api\AuthorController;
use Bio\App\Author\Logic\AuthenticateAuthorFacade;
use Bio\App\Author\Logic\RegisterAuthorFacade;
use Bio\App\Author\Persistence\Author;
use Bio\App\Author\Persistence\AuthorRepository;
use Doctrine\ORM\EntityManager;
use Nette\DI\CompilerExtension;


class AuthorExtension extends CompilerExtension {

    public function loadConfiguration(): void {
        $containerBuilder = $this->getContainerBuilder();

        $containerBuilder->addDefinition($this->prefix('controller'))
                         ->setType(AuthorController::class);

        $containerBuilder->addDefinition($this->prefix('registerAuthorFacade'))
                         ->setType(RegisterAuthorFacade::class);

        $containerBuilder->addDefinition($this->prefix('authenticateAuthorFacade'))
                         ->setType(AuthenticateAuthorFacade::class);
    }



    public function beforeCompile(): void {
        $containerBuilder = $this->getContainerBuilder();

        $containerBuilder->addDefinition($this->prefix('authorRepository'))
                         ->setType(AuthorRepository::class)
                         ->setFactory('@' . EntityManager::class . '::getRepository', [Author::class]);
    }

}
