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
    //classe Request qui permet de récupérer la requête HTTP du formulaire de contact
    //classe MailerInterface issu de symfony qui permet d'envoyer des mails
    #[Route('/contact', 'contact')]
    public function index(Request $request,MailerInterface $mailer ){

        // création d'une instance de classe Contact
    $contact = new Contact();

    //dd($contact);

    //méthode createForrm génère un formulaire basé sur le type de formulaire PublicContactType, lui basé sur l'entité Contact
    $form = $this->createForm(PublicContactType::class, $contact);

    //méthode qui récupère les données de la requête HTTP
    $form->handleRequest($request);

    //si formulaire soumis et valide
    if($form->isSubmitted() && $form->isValid()){

        //dd($form->getData());

        //alors une instance de classe de l'entité email est créé
        $email = new Email();

        //méthode renderView rend un template Twig avec les données du formulaire dans le mail
        $emailTemplate = $this -> renderView('public/contact/template.html.twig',[
            'contact' => $contact,
        ]);
        //récupère le mail de l'utilisateur soumis via le formulaire
        $emailUser = $contact->getEmail();
        //récupère l'objet du message soumis par l'utilisateur via le formulaire
        $emailSubject = $contact ->getObjet();
        //$mailer : méthode ici qui envoie le mail avec les paramètre associés :
        $mailer ->send($email
            // email de l'utilisateur
                ->from($emailUser)
            //sujet de l'email
                ->subject($emailSubject)
            //adresse mail de destination
                ->to ('contact@tournesol-records.com')
            //mise en forme du mail via le template twig fournit précédemment
                ->html($emailTemplate)
        );

        //dd($mailer);

        $this->addFlash('success', 'Message bien envoyé !');
        return $this->redirectToRoute('contact');
    }

    //méthode qui retourne le template associé pour afficher le formulaire de contact a l'utilisateur
    return $this->render('public/contact/index.html.twig', [
        'publicContactFormView' => $form->createView(),
    ]);

    }

}