<?php declare(strict_types=1);

namespace Bio\App\Author\Data;

use Bio\App\EmailAddress\EmailAddress;
use Nette\SmartObject;


class EmailPasswordData {

    use SmartObject;


    /**
     * @var EmailAddress
     */
    protected $emailAddress;

    /**
     * @var string
     */
    protected $password;



    public function __construct(EmailAddress $emailAddress, string $password) {
        $this->emailAddress = $emailAddress;
        $this->password = $password;
    }



    public function getEmailAddress(): EmailAddress {
        return $this->emailAddress;
    }



    public function getPassword(): string {
        return $this->password;
    }

}
