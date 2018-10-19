<?php declare(strict_types=1);


namespace Bio\App\Article\Persistence\Parts;


use Bio\App\Article\Persistence\Part;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
final class ParagraphPart extends Part {

    public function parseData(string $data): string {
        return $data;
    }



    /**
     * @return string
     */
    public function getTextRepresentation(): string {
        return $this->getData();
    }

}
