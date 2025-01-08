<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminDashboardController extends AbstractController
{

    //je créé un controlleur pour accéder a mon dashboard d'admin
    //pour le moment, elle me renvoir directement vers une vue twig
    //j'ai également ajouter dans mon fichier security.yaml le chemin de redirection au submit de ma connexion
    //le chemin redirige vers mon dashboard d'admin
    #[Route('/admin', 'admin_dashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function dashboard(){

        return $this ->render('admin/dashboard.html.twig');
    }

}