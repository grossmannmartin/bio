<?php declare(strict_types=1);

namespace Bio\Core\AccessControl\Signer;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha512;
use Lcobucci\JWT\Token;
use Nette\SmartObject;


class KeySigner implements ISigner {

    use SmartObject;


    /** @var Key */
    private $privateKey;

    /** @var Key */
    private $publicKey;

    /** @var Sha512 */
    private $signer;



    public function __construct(string $privateKeyPath, string $publicKeyPath) {
        $this->privateKey = new Key('file://' . realpath($privateKeyPath));
        $this->publicKey = new Key('file://' . realpath($publicKeyPath));

        $this->signer = new Sha512();
    }



    public function sign(Builder $tokenBuilder): Builder {
        return $tokenBuilder->sign($this->signer, $this->privateKey);
    }



    public function verify(Token $jwtToken): bool {
        return $jwtToken->verify($this->signer, $this->publicKey);
    }

}
