<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager, MailerInterface $mailerInterface): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_USER"]);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $from    = $form->get('email')->getData();
            $email = (new Email())
                ->from($from)
                ->to('dihcar16ar@hotmail.fr')
                ->subject('Inscription')
                ->html('<h1>Bien joué! Vous êtes inscrit.</h1>');
            $mailerInterface->send($email);

            $this->addFlash(
                'notice',
                'Votre compte a été supprimé'
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route('/update/user/{id}', name: 'update_user')]
    public function updateRegister(
        $id,
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        MailerInterface $mailerInterface
    ): Response {
        $user = $userRepository->find($id);

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_USER"]);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $from    = $form->get('email')->getData();
            $email = (new Email())
                ->from($from)
                ->to('dihcar16ar@hotmail.fr')
                ->subject('Mise à jour de votre compte')
                ->html('<h1>Bien joué! Vous avez mis à jour votre compte.</h1>');
            $mailerInterface->send($email);

            $this->addFlash(
                'notice',
                'Le compte a été mis à jour'
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/delete/user/{id}', name: 'delete_user')]
    public function deleteUser($id, UserRepository $userRepository, EntityManagerInterface $entityManagerInterface)
    {
        $user = $userRepository->find($id);

        $entityManagerInterface->remove($user);

        $entityManagerInterface->flush();

        $this->addFlash(
            'notice',
            'Le compte a été supprimé'
        );

        return $this->redirectToRoute("main");
    }
}
