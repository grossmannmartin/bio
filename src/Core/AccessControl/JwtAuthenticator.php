<?php declare(strict_types=1);

namespace Bio\Core\AccessControl;

use Apitte\Core\Exception\Api\ServerErrorException;
use Apitte\Core\Http\RequestAttributes;
use Apitte\Core\Router\IRouter;
use Apitte\Core\Schema\Endpoint;
use Bio\Core\AccessControl\Exceptions\AuthenticationException;
use Bio\Core\AccessControl\Identity\AnonymousIdentity;
use Bio\Core\AccessControl\Identity\Identity;
use Contributte\Middlewares\Security\IAuthenticator;
use Contributte\Psr7\Psr7ServerRequest;
use Nette\SmartObject;
use Nette\Utils\Arrays;
use Psr\Http\Message\ServerRequestInterface;


class JwtAuthenticator implements IAuthenticator {

    use SmartObject;


    private const AUTH_HEADER = 'Authorization';
    private const AUTH_TYPE = 'Bearer';

    public const ACCESS_TAG_NAME = 'access';
    public const ACCESS_PUBLIC = 'public';


    /**
     * @var TokenGenerator
     */
    protected $tokenGenerator;

    /**
     * @var IRouter
     */
    protected $router;



    public function __construct(TokenGenerator $tokenGenerator, IRouter $router) {
        $this->tokenGenerator = $tokenGenerator;
        $this->router = $router;
    }



    public function authenticate(ServerRequestInterface $request) {
        $isPublic = $this->isRequestPublic($request);

        try {
            $identity = $this->getIdentity($request);

            if ($identity) {
                return $identity;
            }
        } catch (AuthenticationException $e) {
        }

        return $isPublic ? new AnonymousIdentity() : null;
    }



    private function isRequestPublic(ServerRequestInterface $request): bool {
        $psr7ServerRequest = $this->router->match($request);

        if ($psr7ServerRequest === null || !($psr7ServerRequest instanceof Psr7ServerRequest)) {
            throw new ServerErrorException('Invalid request');
        }

        /** @var Endpoint $endpoint */
        $endpoint = $psr7ServerRequest->getAttribute(RequestAttributes::ATTR_ENDPOINT);

        return $endpoint->getTag(self::ACCESS_TAG_NAME) === self::ACCESS_PUBLIC;
    }



    /**
     * @throws AuthenticationException
     */
    private function getIdentity(ServerRequestInterface $request): ?Identity {
        if (!$request->hasHeader(self::AUTH_HEADER)) {
            return null;
        }

        $stringToken = $this->parseTokenFromHeader($request->getHeader(self::AUTH_HEADER));

        if ($stringToken === null) {
            return null;
        }

        $identity = $this->tokenGenerator->verify($stringToken);

        if ($identity === null) {
            throw AuthenticationException::invalidToken();
        }

        return $identity;
    }



    private function parseTokenFromHeader(array $headers): ?string {
        foreach ($headers as $header) {
            if (strpos($header, self::AUTH_TYPE) !== 0) {
                continue;
            }

            $tmp = explode(' ', $header);

            if ($token = Arrays::get($tmp, 1, null)) {
                return $token;
            }
        }

        return null;
    }

}
