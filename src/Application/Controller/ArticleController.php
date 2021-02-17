<?php

namespace App\Application\Controller;

use App\Application\Service\AuthService;
use App\Domain\Model\Article;
use App\Domain\Model\Article\ArticleRepositoryInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * Class ArticleController
 * @package App\Application\Controller
 */
class ArticleController extends AbstractFOSRestController
{
    private ArticleRepositoryInterface $articleRepository;
    private SerializerInterface $serializer;
    private string $format;
    private AuthService $authService;
    private string $responseContentType;
    private Article\ArticleFactory $articleFactory;
    private Article\ArticleFieldValidator $articleFieldValidator;


    /**
     * ArticleController constructor.
     * @param ArticleRepositoryInterface $articleRepository
     * @param SerializerInterface $serializer
     * @param AuthService $authService
     * @param Article\ArticleFactory $articleFactory
     * @param Article\ArticleFieldValidator $articleFieldValidator
     * @param string $clientServerCommunicationFormat
     */
    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        SerializerInterface $serializer,
        AuthService $authService,
        Article\ArticleFactory $articleFactory,
        Article\ArticleFieldValidator $articleFieldValidator,
        string $clientServerCommunicationFormat
    )
    {
        $this->articleRepository = $articleRepository;
        $this->serializer = $serializer;
        $this->authService = $authService;
        $this->articleFactory = $articleFactory;
        $this->articleFieldValidator = $articleFieldValidator;
        $this->format = $clientServerCommunicationFormat;

        if ($this->format === 'json')
        {
           $this->responseContentType = 'application/json';
        }
    }

    /**
     * @Route("/article/{articleId}", name="delete_article", methods={"DELETE"})
     *
     * @SWG\Response (
     *     response=204,
     *     description="The article was deleted"
     * )
     * @SWG\Response (
     *     response=403,
     *     description="Forbidden",
     * )
     * @SWG\Response (
     *     response=404,
     *     description="Not Found",
     * )
     * @param int $articleId
     * @param Request $request
     * @return Response
     */
    public function deleteArticleAction(int $articleId, Request $request): Response
    {
        if (!$this->checkAuth($request))
        {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        $article = $this->articleRepository->find($articleId);

        if($article)
        {
            $this->articleRepository->deleteArticle($article);
            $response = new Response('', Response::HTTP_NO_CONTENT);
        }
        else
        {
            $response = new Response('', Response::HTTP_NOT_FOUND);
        }

        $this->prepareResponse($response, $request);
        return $response;
    }

    /**
     * @Route("/article/{articleId}", name="update_article", methods={"PUT"})
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     @SWG\Schema(
     *      required={"title", "body"},
     *      @SWG\Property(
     *          property="title",
     *          description="The article's title",
     *          type="string"
     *      ),
     *      @SWG\Property(
     *          property="body",
     *          description="The article's body",
     *          type="string"
     *      ),
     *    )
     * )
     *
     * @SWG\Response (
     *     response=204,
     *     description="The article was updated"
     *    )
     *
     * @SWG\Response (
     *     response=400,
     *     description="Bad request"
     *    )
     *
     * @SWG\Response (
     *     response=403,
     *     description="Forbidden",
     * )
     * @SWG\Response (
     *     response=404,
     *     description="Not Found",
     * )

     * @param int $articleId
     * @param Request $request
     * @return Response
     */
    public function updateArticleAction(int $articleId, Request $request): Response
    {
        if (!$this->checkAuth($request))
        {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        $article = $this->articleRepository->find($articleId);

        if($article)
        {
            $data = $this->serializer->decode(
                $request->getContent(),
                $this->format
            );

            if($data) {
                try {
                    $this->articleFieldValidator->checkFields($data);
                    $article->setTitle((string)$data['title']);
                    $article->setBody((string)$data['body']);
                    $this->articleRepository->updateArticle($article);
                    $response = new Response('', Response::HTTP_NO_CONTENT);
                }
                catch (Article\InvalidFieldException $exception)
                {
                    $response = new Response('', Response::HTTP_BAD_REQUEST);
                }
            }
            else
            {
                $response = new Response('', Response::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            $response = new Response('', Response::HTTP_NOT_FOUND);
        }

        $this->prepareResponse($response, $request);
        return $response;
    }

    /**
     * @Route("/article/", name="add_article", methods={"POST"})
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     @SWG\Schema(
     *      required={"title", "body"},
     *      @SWG\Property(
     *          property="title",
     *          description="Article's title",
     *          type="string"
     *      ),
     *      @SWG\Property(
     *          property="body",
     *          description="Article's body",
     *          type="string"
     *      ),
     *    )
     * )
     *
     * @SWG\Response (
     *     response=201,
     *     description="Article created",
     *     @SWG\Schema(
     *      @SWG\Property(
     *          property="articleId",
     *          description="Article's primary",
     *          type="integer"
     *      ),
     *    )
     * )
     * @SWG\Response (
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response (
     *     response=403,
     *     description="Forbidden",
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function addArticleAction(Request $request): Response
    {
        if (!$this->checkAuth($request))
        {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        $data = $this->serializer->decode(
            $request->getContent(),
            $this->format
        );

        if($data)
        {
            $article = $this->articleFactory->createArticle($data);

            if($article) {
                $articleId = $this->articleRepository->addArticle($article);

                $response = new Response(
                    $this->serializer->encode(
                        [
                            'articleId' => $articleId
                        ],
                        $this->format
                    ),
                    Response::HTTP_CREATED
                );
            }
            else
            {
                $response = new Response('', Response::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            $response = new Response('', Response::HTTP_BAD_REQUEST);
        }

        $this->prepareResponse($response, $request);
        return $response;
    }

    /**
     * @Route("/article/", name="article_list", methods={"GET"})
     *
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="(asc|desc)",
     *     nullable=true,
     *     default="asc",
     *     description="The articles sorting direction"
     * )
     * @Rest\QueryParam(
     *     name="orderBy",
     *     requirements="[_a-z]+",
     *     nullable=true,
     *     default="id",
     *     description="The articles sorting field"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     nullable=true,
     *     default="10",
     *     description="The articles amount limit"
     * )
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     nullable=true,
     *     default="1",
     *     description="The articles list page number"
     * )
     *
     * @SWG\Response (
     *     response=200,
     *     description="Returns the articles list",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref=@Model(type=Article::class))
     *     )
     * )
     * @SWG\Response (
     *     response=403,
     *     description="Forbidden",
     * )
     *
     * @param Request $request
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getArticlesAction(Request $request, ParamFetcher $paramFetcher): Response
    {
        if (!$this->checkAuth($request))
        {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        $orderBy = $paramFetcher->get('orderBy');
        $order = $paramFetcher->get('order');
        $limit = $paramFetcher->get('limit');
        $page = $paramFetcher->get('page');
        $offset = $this->getOffset($page, $limit);

        $articles = $this->articleRepository->findBy([],[$orderBy => $order], $limit, $offset);

        $response = new Response(
            $this->serializer->serialize(
                $articles,
                $this->format
            ),
            Response::HTTP_OK
        );

        $this->prepareResponse($response, $request);
        return $response;
    }

    /**
     * Prepares the response
     * @param Response $response
     * @param Request $request
     */
    private function prepareResponse(Response $response, Request $request): void
    {
        $response->prepare($request);
        $response->headers->set('Content-Type', $this->responseContentType);
    }

    /**
     * Check auth
     * @param Request $request
     * @return bool
     */
    private function checkAuth(Request $request): bool
    {
        return $this->authService->checkAuth(
            (string)$request->headers->get('X-AUTH-TOKEN')
        );
    }

    /**
     * Calculate offset
     * @param $page
     * @param $limit
     * @return float|int
     */
    private function getOffset($page, $limit)
    {
        $offset = 0;
        if ($page !== 0 && $page !== 1) {
            $offset = ($page - 1) * $limit;
        }

        return $offset;
    }
}
