<?php declare(strict_types=1);


namespace Bio\App\Article\Persistence\Parts;


use Bio\App\Article\Persistence\Part;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
final class ImagePart extends Part {

    public function parseData(string $data): string {
        // TODO: Implement parseData() method.
    }



    /**
     * @return string
     */
    public function getTextRepresentation(): string {
        return '';
    }

}
