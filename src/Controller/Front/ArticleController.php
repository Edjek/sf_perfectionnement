<?php

namespace App\Controller\Front;

use App\Entity\Like;
use App\Repository\LikeRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'article_list')]
    public function articleList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render('front/article/articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function articleShow($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);
        return $this->render('front/article/article.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/search', name: 'search')]
    public function search(ArticleRepository $articleRepository, Request $request)
    {
        $search = $request->query->get('search');

        $articles = $articleRepository->searchByTerm($search);

        return $this->render('front/article/search.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/like/article/{id}', name: 'article_like')]
    public function likeArticle(
        $id,
        ArticleRepository $articleRepository,
        LikeRepository $likeRepository,
        EntityManagerInterface $entityManagerInterface
    ) {

        $article = $articleRepository->find($id);
        $user = $this->getUser();

        if (!$user) {
            return $this->json(
                [
                    'code' => 403,
                    'message' => "Vous devez vous connecter"
                ],
                403
            );
        }

        if ($article->isLikeByUser($user)) {
            $like = $likeRepository->findOneBy(
                [
                    'article' => $article,
                    'user' => $user
                ]
            );

            $entityManagerInterface->remove($like);
            $entityManagerInterface->flush();

            return $this->json([
                'code' => 200,
                'message' => "Like supprimé",
                'likes' => $likeRepository->count(['article' => $article])
            ], 200);
        }


        $like = new Like();

        $like->setArticle($article);
        $like->setUser($user);

        $entityManagerInterface->persist($like);
        $entityManagerInterface->flush();

        return $this->json([
            'code' => 200,
            'message' => "Like ajouté",
            'likes' => $likeRepository->count(['article' => $article])
        ], 200);
    }
}
