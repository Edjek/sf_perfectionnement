<?php

namespace App\Controller\Front;

use App\Repository\WriterRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WriterController extends AbstractController
{
    #[Route('/writers', name: 'writer_list')]
    public function writer_list(WriterRepository $writerRepository)
    {
        $writers = $writerRepository->findAll();

        return $this->render('front/writer/writers.html.twig', [
            'writers' => $writers,
        ]);
    }

    #[Route('/writer/{id}', name: 'writer_show')]
    public function writerShow($id, WriterRepository $writerRepository)
    {
        $writer = $writerRepository->find($id);

        return $this->render('front/writer/writer.html.twig', [
            'writer' => $writer,
        ]);
    }
}
