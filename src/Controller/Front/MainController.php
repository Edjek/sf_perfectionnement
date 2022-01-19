<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function main(): Response
    {
        return $this->render('front/main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
