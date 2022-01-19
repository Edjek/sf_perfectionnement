<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminArticleController extends AbstractController
{
    #[Route('/admin/article', name: 'admin_article_list')]
    public function articleList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render('admin/admin_article/articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/admin/create/article', name: 'admin_create_article')]
    public function createArticle(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $article = new Article();

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Un article a été créé'
            );

            return $this->redirectToRoute("admin_article_list");
        }

        return $this->render("admin/admin_article/articleform.html.twig", ['articleForm' => $articleForm->createView()]);
    }

    #[Route('/admin/update/article/{id}', name: 'admin_update_article')]
    public function updateArticle(
        $id,
        ArticleRepository $articleRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ) {

        $article = $articleRepository->find($id);

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'L\'article a été modifié'
            );

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render("admin/admin_article/articleform.html.twig", ['articleForm' => $articleForm->createView()]);
    }

    #[Route('/admin/delete/article/{id}', name: 'admin_delete_article')]
    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManagerInterface)
    {
        $article = $articleRepository->find($id);

        $entityManagerInterface->remove($article);

        $entityManagerInterface->flush();

        $this->addFlash(
            'notice',
            'L\'article a été supprimé'
        );

        return $this->redirectToRoute("admin_article_list");
    }
}
