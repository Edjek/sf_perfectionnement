<?php

namespace App\Controller\Admin;

use App\Entity\Writer;
use App\Form\WriterType;
use App\Repository\WriterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminWriterController extends AbstractController
{
    public function writerList(WriterRepository $writerRepository)
    {
        $writers = $writerRepository->findAll();

        return $this->render('admin/admin_writer/writers.html.twig', [
            'writers' => $writers,
        ]);
    }

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

    public function adminSearchWriters(WriterRepository $writerRepository, Request $request)
    {
        $search = $request->get('search');

        $writers = $writerRepository->searchByTerm($search);

        return  $this->render("admin/admin_writer/search.html.twig", [
            'writers' => $writers
        ]);
    }
}
