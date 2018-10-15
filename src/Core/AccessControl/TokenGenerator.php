<?php declare(strict_types=1);

namespace Bio\Core\AccessControl;

use Bio\Core\AccessControl\Identity\Identity;
use Bio\Core\AccessControl\Signer\ISigner;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Nette\SmartObject;


final class TokenGenerator {

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
     * @var ISigner
     */
    private $signer;



    public function __construct(string $issuer, string $audience, ISigner $signer) {
        $this->issuer = $issuer;
        $this->audience = $audience;
        $this->signer = $signer;
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

        $signedToken = $this->signer->sign($token);

        return (string)$signedToken->getToken();
    }



    public function verify(string $token): ?Identity {
        $jwtToken = (new Parser())->parse($token);

//         @todo: Check issuer and audience
//         @todo: Check if 'jti' is not blacklisted (also add jti blacklisting ie. user is banned, removed from admin, etc.)

        if ($jwtToken->isExpired() || !$this->signer->verify($jwtToken)) {
            return null;
        }

        return new Identity($jwtToken->getClaim('uid'), (array)$jwtToken->getClaim('data'));
    }

}
