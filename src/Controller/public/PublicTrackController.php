<?php

namespace App\Controller\public;

use App\Repository\ArtisteRepository;
use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicTrackController extends AbstractController
{

    #[Route('/tracks', 'list_tracks', methods: ['GET'])]
    public function listTracks(TrackRepository $trackRepository){

        $tracks = $trackRepository->findAll();

        return $this->render('public/track/list_tracks.html.twig', [
            'tracks' => $tracks
        ]);
    }

    #[Route('/tracks/{id}', 'show_track',requirements:['id' => '\d+'] , methods: ['GET'])]
    public function showTrack(int $id, TrackRepository $trackRepository, ArtisteRepository $artisteRepository){

        $track = $trackRepository->find($id);



        if(!$track){
            $notFoundTrack = new Response('Track non trouvée', 404);
            return $notFoundTrack;
        }

        return $this->render('public/track/show_track.html.twig', [
            'track' => $track,

        ]);
    }

    #[Route('/tracks/search', 'search_tracks', methods: ['GET'])]
    public function searchTracks(Request $request, TrackRepository $trackRepository){

        $search = $request->query->get('search');

        $track = $trackRepository->findBySearchInTitle($search);

        return $this->render('public/track/search_tracks.html.twig', [
            'tracks' => $track,
            'search' => $search
        ]);
    }

}