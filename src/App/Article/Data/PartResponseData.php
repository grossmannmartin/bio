<?php declare(strict_types=1);

namespace Bio\App\Article\Data;

use Apitte\Negotiation\Http\ArrayEntity;
use Bio\App\Article\Persistence\Part;
use Bio\Core\Api\IResponseData;
use Nette\SmartObject;


final class PartResponseData implements IResponseData {

    use SmartObject;

    private $data;



    public function __construct(string $type, string $text) {
        $this->data = [
            'type' => $type,
            'text' => $text,
        ];
    }



    public static function fromPart(Part $part): self {
        return new static($part->getType(), $part->getTextRepresentation());
    }



    public function toArrayEntity(): ArrayEntity {
        return ArrayEntity::from($this->toArray());
    }



    public function toArray(): array {
        return $this->data;
    }



    public function getType(): string {
        return $this->data['type'];
    }



    public function getData(): string {
        return $this->data['text'];
    }

}
