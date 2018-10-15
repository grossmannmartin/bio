<?php declare(strict_types=1);

namespace Bio\App\Author\Persistence;

use Bio\App\EmailAddress\EmailAddress;
use Bio\Core\Utils\Password;
use Doctrine\ORM\Mapping as ORM;
use Nette\SmartObject;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass = "AuthorRepository")
 * @ORM\Table(name = "author",
 *      uniqueConstraints = {
 *          @ORM\UniqueConstraint(name = "author_email_uq", columns = {"email"})
 *      }
 * )
 */
class Author {

    use SmartObject;

    /**
     * @var string
     * @ORM\Column(type = "guid")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type = "string", name = "hashed_password")
     */
    private $hashedPassword;

    /**
     * @var EmailAddress
     * @ORM\Column(type = "email")
     */
    private $email;


    /**
     * @var bool
     * @ORM\Column(type = "boolean", options = {"default" = false})
     */
    private $isAdmin = false;

    /**
     * @var bool
     * @ORM\Column(type = "boolean", options = {"default" = false})
     */
    private $isBanned = false;


    /**
     * @var string
     * @ORM\Column(type = "string")
     */
    private $displayName = '';



    public function __construct(EmailAddress $email, string $password) {
        $this->id = Uuid::uuid1()->toString();
        $this->email = $email;
        $this->hashedPassword = Password::hash($password);
    }



    public function authenticate(string $plaintextPassword): bool {
        return !$this->isBanned && $this->verifyPassword($plaintextPassword);
    }



    public function verifyPassword(string $plaintextPassword): bool {
        return Password::verify($plaintextPassword, $this->hashedPassword);
    }



    public function getDisplayName(): string {
        return $this->displayName ?: (string)$this->email;
    }



    public function makeAdmin(): self {
        $this->isAdmin = true;

        return $this;
    }



    public function ban(): self {
        $this->isBanned = true;

        return $this;
    }



    public function getId(): string {
        return $this->id;
    }



    public function getEmail(): EmailAddress {
        return $this->email;
    }



    public function hasBan(): bool {
        return $this->isBanned;
    }



    public function isAdmin(): bool {
        return $this->isAdmin;
    }

}
