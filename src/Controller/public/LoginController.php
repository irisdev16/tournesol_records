<?php

namespace App\Controller\public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    //je créé une fonction d'authentification grâce â la méthode AuthenticationUtils fournie par Symfony
    //je créé une variable error qui permet d'afficher les erreurs quand on tape l'identifiant ou le mot de passe
    //je créé une variable lastUsername pour récupérer le dernier username entré par l'utilisateur

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response{

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        //dd($error);
        //dd($lastUsername);

        return $this->render('public/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}





