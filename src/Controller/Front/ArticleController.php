<?php

namespace App\Controller\Front;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'article_list')]
    public function article_list(ArticleRepository $articleRepository)
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
}
