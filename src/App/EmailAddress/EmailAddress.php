<?php declare(strict_types=1);

namespace Bio\App\EmailAddress;

use Nette\SmartObject;
use Nette\Utils\Validators;


final class EmailAddress {

    use SmartObject;

    /**
     * @var string|null
     */
    private $name;


    /**
     * @var string
     */
    private $email;



    public function __construct(string $email, ?string $name = null) {
        if (!Validators::isEmail($email)) {
            throw new InvalidEmailAddressException(sprintf('"%s" is not a valid email address', $email));
        }

        $this->email = $email;
        $this->name = $name;
    }



    public function getLocalPart(): string {
        // not decoupled when creating an object, because it is more likely to use a whole email address than just part
        return explode('@', $this->email)[0];
    }



    public function getDomain(): string {
        // not decoupled when creating an object, because it is more likely to use a whole email address than just part
        return explode('@', $this->email)[1];
    }



    public function __toString(): string {
        return $this->get();
    }



    public function get(): string {
        return $this->email;
    }



    public function getDisplayName(): string {
        if (!$this->name) {
            return $this->get();
        }

        return sprintf('%s <%s>', $this->name, $this->email);
    }

}
