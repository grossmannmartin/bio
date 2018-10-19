<?php declare(strict_types=1);

namespace Bio\App\Article\Logic;

use Bio\App\Article\Data\PartResponseData;
use Bio\App\Article\Persistence\Article;
use Bio\App\Article\Persistence\ArticleRepository;
use Bio\App\Article\Persistence\Part;
use Bio\App\Author\Persistence\AuthorRepository;
use Nette\SmartObject;


class CreateArticleFacade {

    use SmartObject;

    /**
     * @var ArticleRepository
     */
    protected $articleRepository;

    /**
     * @var AuthorRepository
     */
    protected $authorRepository;



    public function __construct(ArticleRepository $articleRepository, AuthorRepository $authorRepository) {
        $this->articleRepository = $articleRepository;
        $this->authorRepository = $authorRepository;

    }



    public function createAndPublishArticle(string $heading, string $authorId, string $lead, array $content, string $tag = null): Article {
        $article = $this->prepareArticle($heading, $authorId, $lead, $content, $tag);

        $article->publish();

        $this->articleRepository->persist($article);

        return $article;
    }



    private function prepareArticle(string $heading, string $authorId, string $lead, array $content, string $tag = null): Article {
        $author = $this->authorRepository->getById($authorId);

        $article = new Article($heading, $author, $lead, $tag);

        /** @var PartResponseData $contentPart */
        foreach ($content as $sort => $contentPart) {
            $part = Part::createTyped($contentPart->getType(), $contentPart->getData());

            $part->setSort((int)$sort);
            $article->addPart($part);
        }

        return $article;
    }

}
