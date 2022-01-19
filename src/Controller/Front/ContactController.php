<?php

namespace App\Controller\Front;

use App\Form\ContactType;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailerInterface): Response
    {
        $contactForm = $this->createForm(ContactType::class);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $from    = $contactForm->get('email')->getData();
            $subject = $contactForm->get('subject')->getData();
            $message = $contactForm->get('message')->getData();

            // $email = (new Email())
            //     ->from($from)
            //     ->to('dihcar16ar@hotmail.fr')
            //     ->subject($subject)
            //     ->html($message);
            // $mailerInterface->send($email);

            $email = (new TemplatedEmail())
                ->from($from)
                ->to(new Address('ryan@example.com'))
                ->subject($subject)

                // path of the Twig template to render
                ->htmlTemplate('front/contact/email.html.twig')

                // pass variables (name => value) to the template
                ->context([
                    'expiration_date' => new \DateTime('+7 days'),
                    'username' => 'foo',
                    'message' => $message,
                ]);

            $mailerInterface->send($email);

            $this->addFlash(
                'notice',
                'Votre message a été envoyé'
            );

            return $this->redirectToRoute('main');
        }

        return $this->render('front/contact/contactform.html.twig', [
            'contactForm' => $contactForm->createView(),
        ]);
    }
}
