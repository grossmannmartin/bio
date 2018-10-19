<?php declare(strict_types=1);

namespace Bio\App\Article\Persistence;


use Bio\Exceptions\Runtime\EntryNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;

class ArticleRepository extends EntityRepository {

    public function persist(Article $article): void {
        $this->getEntityManager()->persist($article);
        $this->getEntityManager()->flush($article);
    }



    public function em(): EntityManager {
        return $this->getEntityManager();

    }



    /**
     * @throws EntryNotFoundException
     */
    public function getPublishedArticle(string $id): Article {
        if (!Uuid::isValid($id)) {
            throw EntryNotFoundException::invalidId();
        }

        /** @var Article|null $article */
        $article = $this->findOneBy(['id' => $id, 'isPublished' => true]);

        if ($article === null) {
            throw EntryNotFoundException::notFound();
        }

        return $article;
    }



    /**
     * @return Article[]
     */
    public function findAllPublished(): array {
        return $this->findBy(['isPublished' => true], ['publishedAt' => 'DESC']);
    }



    /**
     * @return Article[]
     */
    public function findPublishedArticleByTag(string $tag): array {
        return $this->findBy(['isPublished' => true, 'tag' => $tag], ['publishedAt' => 'DESC']);
    }



    /**
     * @throws EntryNotFoundException
     */
    public function getArticle(string $id): Article {
        /** @var Article|null $article */
        $article = $this->findOneBy(['id' => $id]);

        if ($article === null) {
            throw EntryNotFoundException::notFound();
        }

        return $article;
    }

}
