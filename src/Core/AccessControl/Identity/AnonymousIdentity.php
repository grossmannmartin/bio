<?php declare(strict_types=1);

namespace Bio\Core\AccessControl\Identity;

use Nette\SmartObject;


class AnonymousIdentity extends Identity {

    use SmartObject;



    public function __construct() {
        parent::__construct('', []);
    }

}
