<?php declare(strict_types=1);

namespace Bio\App\Author\Api;


use Apitte\Core\Annotation\Controller\Controller;
use Apitte\Core\Annotation\Controller\ControllerPath;
use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Annotation\Controller\RequestParameters;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\Core\UI\Controller\IController;
use Apitte\Negotiation\Http\ArrayEntity;
use Bio\App\Author\Data\AuthorResponseData;
use Bio\App\Author\Logic\AuthenticateAuthorFacade;
use Bio\App\Author\Logic\RegisterAuthorFacade;
use Bio\App\Author\Persistence\Author;
use Bio\App\Author\Persistence\AuthorRepository;
use Bio\Core\AccessControl\Exceptions\AuthenticationException;
use Bio\Core\AccessControl\TokenGenerator;
use Bio\Exceptions\Runtime\DuplicateEntryException;
use Bio\Exceptions\Runtime\EntryNotFoundException;
use Bio\Exceptions\Runtime\MalformedInputException;
use Nette\SmartObject;
use Psr\Http\Message\ResponseInterface;

/**
 * @Controller
 * @ControllerPath("/authors")
 */
final class AuthorController implements IController {

    use SmartObject;

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    /**
     * @var AuthenticateAuthorFacade
     */
    private $authenticateAuthorFacade;


    /**
     * @var RegisterAuthorFacade
     */
    private $registerAuthorFacade;

    /**
     * @var AuthorRepository
     */
    private $authorRepository;



// @todo: Split into smaller controllers
    public function __construct(
        RegisterAuthorFacade $registerUserFacade,
        AuthenticateAuthorFacade $authenticateAuthorFacade,
        AuthorRepository $authorRepository,
        TokenGenerator $tokenGenerator
    ) {
        $this->registerAuthorFacade = $registerUserFacade;
        $this->authorRepository = $authorRepository;
        $this->authenticateAuthorFacade = $authenticateAuthorFacade;
        $this->tokenGenerator = $tokenGenerator;
    }



    /**
     * @Path("/author/{id}")
     * @Method("GET")
     * @RequestParameters({
     * 		@RequestParameter(name="id", type="string")
     * })
     */
    public function detail(ApiRequest $request, ApiResponse $response): ResponseInterface {
        try {
            $author = $this->authorRepository->getById($request->getParameter('id'));

            return $response->withEntity(AuthorResponseData::fromAuthor($author)->toArrayEntity());

        } catch (EntryNotFoundException $e) {
            throw ClientErrorException::create()
                                      ->withCode(404)
                                      ->withMessage($e->getMessage());
        }
    }



    /**
     * @Path("/")
     * @Method("GET")
     * @todo: Implement 'next' behaviour
     */
    public function list(ApiRequest $request, ApiResponse $response): ResponseInterface {
        $authors = $this->authorRepository->findAll();

        $authorsData = array_map(
            function (Author $author) {
                return AuthorResponseData::fromAuthor($author)->toArray();
            },
            $authors
        );

        return $response->withEntity(ArrayEntity::from($authorsData));
    }



    /**
     * @Path("/register")
     * @Method("POST")
     */
    public function register(ApiRequest $request, ApiResponse $response): ResponseInterface {
        try {
            $author = $this->registerAuthorFacade->registerAuthor(AuthorRequestMapper::dataFromRequest($request));

            return $response->withEntity(AuthorResponseData::fromAuthor($author)->toArrayEntity())
                            ->withStatus(201)
                // @todo: Solve better when LinkGenerator will be introduced in Apitte
                            ->withHeader('location', 'http' . ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 's' : '') . "://{$_SERVER['HTTP_HOST']}/authors/author/{$author->getId()}");

        } catch (DuplicateEntryException|MalformedInputException $e) {
            throw ClientErrorException::create()
                                      ->withCode(409)
                                      ->withMessage($e->getMessage());
        }
    }



    /**
     * @Path("/login")
     * @Method("POST")
     *
     * @Tag(name=Bio\Core\AccessControl\JwtAuthenticator::ACCESS_TAG_NAME, value=Bio\Core\AccessControl\JwtAuthenticator::ACCESS_PUBLIC)
     */
    public function login(ApiRequest $request, ApiResponse $response): ResponseInterface {
        try {
            $author = $this->authenticateAuthorFacade->authenticate(AuthorRequestMapper::dataFromRequest($request));

            $token = $this->tokenGenerator->generate($author->getId(), ['email' => (string)$author->getEmail(), 'isAdmin' => $author->isAdmin()]);

            return $response->withEntity(ArrayEntity::from(['token' => $token]));
        } catch (MalformedInputException $e) {
            throw ClientErrorException::create()
                                      ->withCode(409)
                                      ->withMessage($e->getMessage());
        } catch (AuthenticationException $e) {
            throw ClientErrorException::create()
                                      ->withCode(401)
                                      ->withMessage($e->getMessage());
        }
    }

}
