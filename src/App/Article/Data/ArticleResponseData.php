<?php declare(strict_types=1);

namespace Bio\App\Article\Data;

use Apitte\Negotiation\Http\ArrayEntity;
use Bio\App\Article\Persistence\Article;
use Bio\App\Article\Persistence\Part;
use Bio\App\Author\Persistence\Author;
use Bio\Core\Api\IResponseData;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Nette\SmartObject;


final class ArticleResponseData implements IResponseData {

    use SmartObject;

    private $data;



    /**
     * @param Part[]|Collection $content
     */
    public function __construct(string $id, string $heading, Author $author, DateTimeImmutable $publishedAt, $lead, $content) {
        $this->data = [
            'id' => $id,
            'heading' => $heading,
            'authorName' => $author->getDisplayName(),
            'authorEmail' => (string)$author->getEmail(),
            'publishedAt' => $publishedAt->format(DATE_ATOM),
            'lead' => $lead,
            'content' => [],
        ];

        foreach ($content as $contentPart) {
            $this->data['content'][] = PartResponseData::fromPart($contentPart)->toArray();
        }
    }



    /**
     * Create new Response Data Object with values from passed article.
     * Data will contain full article (whole content)
     */
    public static function fromArticle(Article $article): self {
        return new static(
            $article->getId(),
            $article->getHeading(),
            $article->getAuthor(),
            $article->getPublishedTime(),
            $article->getLead(),
            $article->getParts()
        );
    }



    /**
     * Create new Response Data Object with values from passed article.
     * Data will be without content (useful for listing)
     */
    public static function stubFromArticle(Article $article): self {
        return new static(
            $article->getId(),
            $article->getHeading(),
            $article->getAuthor(),
            $article->getPublishedTime(),
            $article->getLead(),
            []
        );
    }



    public function toArrayEntity(): ArrayEntity {
        return ArrayEntity::from($this->toArray());
    }



    public function toArray(): array {
        return $this->data;
    }

}
