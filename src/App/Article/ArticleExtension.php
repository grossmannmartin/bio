<?php declare(strict_types=1);

namespace Bio\App\Article;

use Bio\App\Article\Api\ArticleController;
use Bio\App\Article\Logic\CreateArticleFacade;
use Bio\App\Article\Logic\UpdateArticleFacade;
use Bio\App\Article\Persistence\Article;
use Bio\App\Article\Persistence\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Nette\DI\CompilerExtension;


class ArticleExtension extends CompilerExtension {

    public function loadConfiguration(): void {
        $containerBuilder = $this->getContainerBuilder();

        $containerBuilder->addDefinition($this->prefix('controller'))
                         ->setType(ArticleController::class);

        $containerBuilder->addDefinition($this->prefix('createArticleFacade'))
                         ->setType(CreateArticleFacade::class);

        $containerBuilder->addDefinition($this->prefix('updateArticleFacade'))
                         ->setType(UpdateArticleFacade::class);
    }



    public function beforeCompile(): void {
        $containerBuilder = $this->getContainerBuilder();

        $containerBuilder->addDefinition($this->prefix('articleRepository'))
                         ->setType(ArticleRepository::class)
                         ->setFactory('@' . EntityManager::class . '::getRepository', [Article::class]);
    }

}
