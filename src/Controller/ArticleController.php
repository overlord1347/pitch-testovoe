<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/article", name="article_")
 */
class ArticleController extends AbstractController
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/get", name="articles", methods={"GET"})
     */
    public function getArticles(Request $request, SerializerInterface $serializer): Response
    {
        $category = $request->query->get('category', '');
        $pageId = $request->query->getInt('page', 1);

        $articles = $this->articleRepository->getArticlesByCategories($pageId, $category);

        $articlesJson = $serializer->serialize($articles, 'json');

        return new Response($articlesJson);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     *
     * @return Response
     */
    public function createArticle(Request $request, ValidatorInterface $validator): Response
    {
        $article = new Article();

        $request = $request->request;
        $article->setAuthor($request->get('author'))
            ->setCategory($request->get('category'))
            ->setTitle($request->get('title'));

        $errors = $validator->validate($article);
        if (count($errors) > 0) {
            return new Response($errors->get(0)->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->articleRepository->add($article, true);
        } catch (\Throwable $exception) {
            // todo add logger
            return $this->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json('ok');
    }
}
