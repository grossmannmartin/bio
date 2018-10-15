<?php declare(strict_types=1);


namespace Bio\Core\AccessControl\Signer;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;

interface ISigner {

    public function sign(Builder $tokenBuilder): Builder;



    public function verify(Token $jwtToken): bool;

}
