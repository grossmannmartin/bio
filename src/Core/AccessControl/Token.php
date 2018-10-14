<?php declare(strict_types=1);

namespace Bio\Core\AccessControl;

use Bio\Core\AccessControl\Identity\Identity;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha512;
use Nette\SmartObject;


final class Token {

    use SmartObject;


    /**
     * @var string
     */
    private $issuer;

    /**
     * @var string
     */
    private $audience;

    /**
     * @var Key
     */
    private $privateKey;

    /**
     * @var Key
     */
    private $publicKey;

    /**
     * @var Sha512
     */
    private $signer;



    public function __construct(string $issuer, string $audience, string $privateKeyPath, string $publicKeyPath) {
        $this->privateKey = new Key('file://' . realpath($privateKeyPath));
        $this->publicKey = new Key('file://' . realpath($publicKeyPath));

        $this->issuer = $issuer;
        $this->audience = $audience;
        $this->signer = new Sha512();
    }



    // @todo: Add refresh token and shorten expiration of Auth Token
    public function generate(string $id, array $data = []): string {
        $token = new Builder();

        $token->setIssuer($this->issuer)
              ->setAudience($this->audience)
            // @todo: Do not use static 'jti'
              ->setId('4f1g23a12aa', true)
              ->setIssuedAt(time())
              ->setExpiration(time() + 86400)
              ->set('uid', $id)
              ->set('data', $data);

        $token->sign($this->signer, $this->privateKey);

        return (string)$token->getToken();
    }



    public function verify(string $token): ?Identity {
        $jwtToken = (new Parser())->parse($token);

//         @todo: Check issuer and audience
//         @todo: Check if 'jti' is not blacklisted (also add jti blacklisting ie. user is banned, removed from admin, etc.)

        if ($jwtToken->isExpired() || !$jwtToken->verify($this->signer, $this->publicKey)) {
            return null;
        }

        return new Identity($jwtToken->getClaim('uid'), (array)$jwtToken->getClaim('data'));
    }

}
