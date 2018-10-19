<?php declare(strict_types=1);

namespace Bio\App\Article\Logic;

use Bio\App\Article\Data\PartResponseData;
use Bio\App\Article\Persistence\Article;
use Bio\App\Article\Persistence\ArticleRepository;
use Bio\App\Article\Persistence\Part;
use Bio\App\Author\Persistence\AuthorRepository;
use Bio\Exceptions\Runtime\EntryNotFoundException;
use Nette\SmartObject;


class UpdateArticleFacade {

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



    /**
     * @throws EntryNotFoundException
     */
    public function updateArticle(string $id, ?string $heading = null, ?array $content = null, ?string $lead = null, ?string $tag = null): Article {
        $article = $this->prepareArticle($id, $heading, $content, $lead, $tag);

        $this->articleRepository->persist($article);

        return $article;
    }



    /**
     * @throws EntryNotFoundException
     */
    private function prepareArticle(string $id, ?string $heading, ?array $content, ?string $lead, ?string $tag): Article {
        $article = $this->articleRepository->getArticle($id);

        if ($heading !== null) {
            $article->setHeading($heading);
        }

        if ($tag !== null) {
            $article->setTag($tag);
        }

        if ($lead !== null) {
            $article->setLead($lead);
        }

        if ($content !== null) {
            $article->removeAllParts();

            /** @var PartResponseData $contentPart */
            foreach ($content as $sort => $contentPart) {
                $part = Part::createTyped($contentPart->getType(), $contentPart->getData());

                $part->setSort((int)$sort);
                $article->addPart($part);
            }
        }

        return $article;
    }

}
