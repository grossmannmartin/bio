<?php declare(strict_types=1);

namespace Bio\App\Article\Persistence;

use Bio\App\Author\Persistence\Author;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nette\SmartObject;
use Ramsey\Uuid\Uuid;


/**
 * @ORM\Entity(repositoryClass = "ArticleRepository")
 * @ORM\Table(name = "article")
 */
class Article {

    use SmartObject;

    /**
     * @var string
     * @ORM\Column(type = "guid")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type = "string")
     */
    private $heading;

    /**
     * @var string
     * @ORM\Column(type = "text")
     */
    private $lead;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type = "datetime_immutable")
     */
    private $createdAt;

    /**
     * @var bool
     * @ORM\Column(type = "boolean", options = {"default" = false})
     */
    private $isPublished = false;

    /**
     * @var DateTimeImmutable|null
     * @ORM\Column(type = "datetime_immutable", nullable = true)
     */
    private $publishedAt;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable = true)
     */
    private $tag;

    /**
     * @var Part[]|Collection
     * @ORM\OneToMany(targetEntity = "Part", mappedBy = "article", cascade = {"persist", "refresh"}, orphanRemoval = true)
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $parts;

    /**
     * @var Author
     * @ORM\ManyToOne(targetEntity = "Bio\App\Author\Persistence\Author")
     * @ORM\JoinColumn(nullable = false)
     */
    private $author;



    public function __construct(string $heading, Author $author, string $lead, ?string $tag = null) {
        $this->id = Uuid::uuid1()->toString();
        $this->parts = new ArrayCollection();
        $this->heading = $heading;
        $this->createdAt = new DateTimeImmutable();
        $this->author = $author;
        $this->tag = $tag;
        $this->lead = $lead;
    }



    public function getParts(): Collection {
        return $this->parts;
    }



    public function getId(): string {
        return $this->id;
    }



    public function publish(): self {
        $this->publishedAt = new DateTimeImmutable();
        $this->isPublished = true;

        return $this;
    }



    public function withDraw(): self {
        $this->publishedAt = null;
        $this->isPublished = false;

        return $this;
    }



    public function getHeading(): string {
        return $this->heading;
    }



    public function getAuthor(): Author {
        return $this->author;
    }



    public function getPublishedTime(): ?DateTimeImmutable {
        if (!$this->isPublished) {
            return null;
        }

        return $this->publishedAt;
    }



    public function addPart(Part $part): self {
        $this->parts[] = $part;
        $part->setArticle($this);

        return $this;
    }



    public function setHeading(string $heading): self {
        $this->heading = $heading;

        return $this;
    }



    public function setLead(string $lead): self {
        $this->lead = $lead;

        return $this;
    }



    public function setTag(?string $tag): self {
        $this->tag = $tag;

        return $this;
    }



    public function removeAllParts(): self {
        $this->parts = new ArrayCollection();

        return $this;
    }



    public function getLead(): string {
        return $this->lead;
    }


}
