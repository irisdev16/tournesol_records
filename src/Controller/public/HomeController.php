<?php

namespace App\Controller\public;

use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', 'homepage', methods: ['GET'])]
    public function showHomePage(TrackRepository $trackRepository) {

        $tracks = $trackRepository->findAll();

        return $this->render('public/homepage.html.twig', [
            'tracks' => $tracks,
        ]);

    }
}