<?php declare(strict_types=1);

namespace Bio\App\Author\Data;

use Apitte\Negotiation\Http\ArrayEntity;
use Bio\App\Author\Persistence\Author;
use Bio\App\EmailAddress\EmailAddress;
use Bio\Core\Api\IResponseData;
use Nette\SmartObject;


class AuthorResponseData implements IResponseData {

    use SmartObject;


    /**
     * @var string
     */
    protected $id;

    /**
     * @var EmailAddress
     */
    protected $email;

    /**
     * @var string
     */
    protected $displayName;



    public function __construct(string $id, EmailAddress $email, string $displayName) {
        $this->id = $id;
        $this->email = $email;
        $this->displayName = $displayName;
    }



    public static function fromAuthor(Author $author): self {
        return new static($author->getId(), $author->getEmail(), $author->getDisplayName());
    }



    public function toArrayEntity(): ArrayEntity {
        return ArrayEntity::from($this->toArray());
    }



    public function toArray(): array {
        return [
            'id' => $this->id,
            'email' => (string)$this->email,
            'displayName' => $this->displayName,
        ];
    }

}
