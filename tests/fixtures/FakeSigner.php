<?php declare(strict_types=1);

namespace BioTest\Fixtures;

use Bio\Core\AccessControl\Signer\ISigner;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;
use Nette\SmartObject;


class FakeSigner implements ISigner {

    use SmartObject;



    public function sign(Builder $tokenBuilder): Builder {
        return $tokenBuilder;
    }



    public function verify(Token $jwtToken): bool {
        return true;
    }

}
