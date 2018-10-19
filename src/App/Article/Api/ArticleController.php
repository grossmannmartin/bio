<?php

namespace Bio\App\Article\Api;


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
use Bio\App\Article\Data\ArticleResponseData;
use Bio\App\Article\Logic\CreateArticleFacade;
use Bio\App\Article\Logic\UpdateArticleFacade;
use Bio\App\Article\Persistence\Article;
use Bio\App\Article\Persistence\ArticleRepository;
use Bio\Core\AccessControl\Identity\Identity;
use Bio\Exceptions\Runtime\EntryNotFoundException;
use Contributte\Middlewares\SecurityMiddleware;
use Psr\Http\Message\ResponseInterface;

/**
 * @Controller
 * @ControllerPath("/articles")
 */
final class ArticleController implements IController {

    /**
     * @var UpdateArticleFacade
     */
    private $updateArticleFacade;

    /**
     * @var CreateArticleFacade
     */
    private $createArticleFacade;

    /**
     * @var ArticleRepository
     */
    private $articleRepository;



    public function __construct(ArticleRepository $articleRepository, CreateArticleFacade $createArticleFacade, UpdateArticleFacade $updateArticleFacade) {
        $this->articleRepository = $articleRepository;
        $this->createArticleFacade = $createArticleFacade;
        $this->updateArticleFacade = $updateArticleFacade;
    }



    /**
     * @Path("/get/{id}")
     * @Method("GET")
     * @RequestParameters({
     * 		@RequestParameter(name="id", type="string")
     * })
     *
     * @Tag(name=Bio\Core\AccessControl\JwtAuthenticator::ACCESS_TAG_NAME, value=Bio\Core\AccessControl\JwtAuthenticator::ACCESS_PUBLIC)
     */
    public function getArticle(ApiRequest $request, ApiResponse $response): ResponseInterface {
        $id = $request->getParameter('id');

        try {
            $article = $this->articleRepository->getPublishedArticle($id);

            return $response->withEntity(ArticleResponseData::fromArticle($article)->toArrayEntity());

        } catch (EntryNotFoundException $e) {
            throw ClientErrorException::create()
                                      ->withCode(404)
                                      ->withMessage($e->getMessage());
        }
    }



    /**
     * @Path("/tag/{tag}")
     * @Method("GET")
     * @RequestParameters({
     * 		@RequestParameter(name="tag", type="string")
     * })
     *
     * @Tag(name=Bio\Core\AccessControl\JwtAuthenticator::ACCESS_TAG_NAME, value=Bio\Core\AccessControl\JwtAuthenticator::ACCESS_PUBLIC)
     */
    public function findArticlesByTag(ApiRequest $request, ApiResponse $response): ResponseInterface {
        $tag = $request->getParameter('tag');


        $articles = $this->articleRepository->findPublishedArticleByTag($tag);

        $articlesData = array_map(
            function (Article $article) {
                return ArticleResponseData::fromArticle($article)->toArray();
            },
            $articles
        );

        return $response->withEntity(ArrayEntity::from($articlesData));
    }



    /**
     * @Path("/list")
     * @Method("GET")
     *
     * @Tag(name=Bio\Core\AccessControl\JwtAuthenticator::ACCESS_TAG_NAME, value=Bio\Core\AccessControl\JwtAuthenticator::ACCESS_PUBLIC)
     */
    public function listArticles(ApiRequest $request, ApiResponse $response): ResponseInterface {
        $articles = $this->articleRepository->findAllPublished();

        $articlesData = array_map(
            function (Article $article) {
                return ArticleResponseData::stubFromArticle($article)->toArray();
            },
            $articles
        );

        return $response->withEntity(ArrayEntity::from($articlesData));
    }



    /**
     * @Path("/create")
     * @Method("POST")
     */
    public function create(ApiRequest $request, ApiResponse $response): ResponseInterface {
        /** @var Identity $identity */
        $identity = $request->getAttribute(SecurityMiddleware::ATTR_IDENTITY);

        $articleData = ArticleRequestMapper::dataFromRequest($request);

        $article = $this->createArticleFacade->createAndPublishArticle($articleData->getHeading(), $identity->getId(), $articleData->getLead(), $articleData->getContent());

        return $response->withEntity(ArticleResponseData::fromArticle($article)->toArrayEntity());
    }



    /**
     * @Path("/update/{id}")
     * @Method("PUT")
     * @RequestParameters({
     * 		@RequestParameter(name="id", type="string")
     * })
     */
    public function update(ApiRequest $request, ApiResponse $response): ResponseInterface {
        $id = $request->getParameter('id');
        $articleData = ArticleRequestMapper::dataFromRequest($request);

        try {
            $article = $this->updateArticleFacade->updateArticle($id, $articleData->getHeading(), $articleData->getContent());

            return $response->withEntity(ArticleResponseData::fromArticle($article)->toArrayEntity());

        } catch (EntryNotFoundException $e) {
            throw ClientErrorException::create()
                                      ->withCode(404)
                                      ->withMessage($e->getMessage());
        }
    }

}
