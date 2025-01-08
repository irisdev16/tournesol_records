<?php

namespace App\Controller\public;

use App\Repository\ArtisteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicArtistController extends AbstractController
{

    #[Route('/artists', 'list_artists', methods: ['GET'])]
    public function listArtists(ArtisteRepository $artisteRepository){

        $artists = $artisteRepository->findAll();

        return $this->render('public/artist/list_artists.html.twig', [
            'artists' => $artists
        ]);
    }

    #[Route('/artists/{id}', 'show_artist', methods: ['GET'], requirements:['id' => '\d+'] )]
    public function showArtist(int $id, ArtisteRepository $artisteRepository){

        $artist = $artisteRepository->find($id);

        if(!$artist){
            $notFoundArtist = new Response('Artiste non trouvé !', 404);
            return $notFoundArtist;
        }

        return $this->render('public/artist/show_artist.html.twig', [
            'artist' => $artist
        ]);
    }

    #[Route('/artists/search', 'search_artists', methods: ['GET'])]
    public function searchArtists(Request $request, ArtisteRepository $artisteRepository){

        $search = $request->query->get('search');

        $artist = $artisteRepository ->findBySearchInTitle($search);

        return $this->render('public/artist/search_artists.html.twig', [
            'artists' => $artist,
            'search' => $search
        ]);
    }


}