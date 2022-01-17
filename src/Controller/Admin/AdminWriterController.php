<?php

namespace App\Controller\Admin;

use App\Entity\Writer;
use App\Form\WriterType;
use App\Repository\WriterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminWriterController extends AbstractController
{
    #[Route('/admin/writer', name: 'admin_writer_list')]
    public function comic_list(WriterRepository $writerRepository)
    {
        $writers = $writerRepository->findAll();

        return $this->render('admin/admin_writer/writers.html.twig', [
            'writers' => $writers,
        ]);
    }

    #[Route('/admin/create/writer', name: 'admin_create_writer')]
    public function createWriter(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $writer = new Writer();

        $writerForm = $this->createForm(WriterType::class, $writer);

        $writerForm->handleRequest($request);

        if ($writerForm->isSubmitted() && $writerForm->isValid()) {
            $entityManagerInterface->persist($writer);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Un auteur a été créé'
            );

            return $this->redirectToRoute("admin_writer_list");
        }

        return $this->render("admin/admin_writer/writerform.html.twig", ['writerForm' => $writerForm->createView()]);
    }

    #[Route('/admin/update/writer/{id}', name: 'admin_update_writer')]
    public function updateWriter(
        $id,
        WriterRepository $writerRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ) {

        $writer = $writerRepository->find($id);

        $writerForm = $this->createForm(WriterType::class, $writer);

        $writerForm->handleRequest($request);

        if ($writerForm->isSubmitted() && $writerForm->isValid()) {
            $entityManagerInterface->persist($writer);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'L\'auteur a été modifié'
            );

            return $this->redirectToRoute('admin_writer_list');
        }

        return $this->render("admin/admin_writer/writerform.html.twig", ['writerForm' => $writerForm->createView()]);
    }

    #[Route('/admin/delete/writer/{id}', name: 'admin_delete_writer')]
    public function deleteWriter($id, WriterRepository $writerRepository, EntityManagerInterface $entityManagerInterface)
    {
        $writer = $writerRepository->find($id);

        $entityManagerInterface->remove($writer);

        $entityManagerInterface->flush();

        $this->addFlash(
            'notice',
            'L\'auteur a été supprimé'
        );

        return $this->redirectToRoute("admin_writer_list");
    }
}
