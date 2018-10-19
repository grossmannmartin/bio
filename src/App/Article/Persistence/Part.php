<?php declare(strict_types=1);

namespace Bio\App\Article\Persistence;


use Bio\App\Article\Persistence\Parts\ImagePart;
use Bio\App\Article\Persistence\Parts\ListPart;
use Bio\App\Article\Persistence\Parts\ParagraphPart;
use Bio\App\Article\Persistence\Parts\UnknownPartTypeException;
use Doctrine\ORM\Mapping as ORM;
use Nette\SmartObject;
use Ramsey\Uuid\Uuid;
use function get_class;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name = "type", type = "string")
 * @ORM\DiscriminatorMap({
 *      Part::PART_TYPE_PARAGRAPH = ParagraphPart::class,
 *      Part::PART_TYPE_LIST = ListPart::class,
 *      Part::PART_TYPE_IMAGE = ImagePart::class,
 * })
 */
abstract class Part {

    use SmartObject;

    public const PART_TYPE_PARAGRAPH = 'paragraph';
    public const PART_TYPE_LIST = 'list';
    public const PART_TYPE_IMAGE = 'image';

    public static $availableParts = [
        self::PART_TYPE_PARAGRAPH,
        self::PART_TYPE_LIST,
        self::PART_TYPE_IMAGE,
    ];

    /**
     * @var string
     * @ORM\Column(type = "guid")
     * @ORM\Id
     */
    private $id;


    /**
     * @var Article
     * @ORM\ManyToOne(targetEntity = "Article", inversedBy = "parts")
     * @ORM\JoinColumn(nullable = false)
     */
    private $article;


    /**
     * @var int
     * @ORM\Column(type = "integer", nullable = false, options = {"default": 0})
     */
    private $sort;

    /**
     * @var string
     * @ORM\Column(type = "text", nullable = false)
     */
    private $data;



    final public function __construct(string $data) {
        $this->id = Uuid::uuid1()->toString();
        $this->sort = 0;
        $this->data = $this->parseData($data);
    }



    public static function createTyped(string $type, string $data): Part {
        switch ($type) {
            case self::PART_TYPE_PARAGRAPH:
                return new ParagraphPart($data);
                break;

            case self::PART_TYPE_LIST:
                return new ListPart($data);
                break;

            case self::PART_TYPE_IMAGE:
                return new ImagePart($data);
                break;

            default:
                throw UnknownPartTypeException::unknownType($type, self::$availableParts);
        }
    }



    public function getType(): string {
        switch (get_class($this)) {
            case ParagraphPart::class:
                return self::PART_TYPE_PARAGRAPH;
                break;
            case ImagePart::class:
                return self::PART_TYPE_IMAGE;
                break;
            case ListPart::class:
                return self::PART_TYPE_LIST;
                break;
            default:
                // Should never occur
                throw new UnknownPartTypeException('Unable to determine a discriminator.');
        }
    }



    abstract public function parseData(string $data): string;



    abstract public function getTextRepresentation(): string;



    public function setArticle(Article $article): void {
        $this->article = $article;
    }



    public function setSort(int $sort): void {
        $this->sort = $sort;
    }



    protected function getData(): string {
        return $this->data;
    }

}
