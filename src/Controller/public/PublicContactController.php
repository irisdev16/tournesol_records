<?php

namespace App\Controller\public;

use App\Entity\Contact;
use App\Form\PublicContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class PublicContactController extends AbstractController
{

    #[Route('/contact', 'contact')]
    public function index(Request $request,MailerInterface $mailer ){

    $contact = new Contact();

    $form = $this->createForm(PublicContactType::class, $contact);

    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){

        $email = new Email();

        $emailTemplate = $this -> renderView('public/contact/template.html.twig',[
            'contact' => $contact,
        ]);

        $emailUser = $contact->getEmail();
        $emailSubject = $contact ->getObjet();

        $mailer ->send($email
                ->from($emailUser)
                ->subject($emailSubject)
                ->to ('contact@tournesol-records.com')
                ->html($emailTemplate)
        );

        $this->addFlash('success', 'Message bien envoyé !');
        return $this->redirectToRoute('contact');
    }

    return $this->render('public/contact/index.html.twig', [
        'publicContactFormView' => $form->createView(),
    ]);

    }

}