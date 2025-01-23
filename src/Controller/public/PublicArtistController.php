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

        //dd($artists);

            //---------------REQUÊTE SQL GÉNÉRÉE--------------
        //SELECT (alias, description, image)
        //FROM artiste

        return $this->render('public/artist/list_artists.html.twig', [
            'artists' => $artists
        ]);
    }

    #[Route('/artists/{id}', 'show_artist', methods: ['GET'], requirements:['id' => '\d+'] )]
    public function showArtist(int $id, ArtisteRepository $artisteRepository){

        $artist = $artisteRepository->find($id);

        //dd($artist);

        if(!$artist){
            $notFoundArtist = new Response('Artiste non trouvé !', 404);
            return $notFoundArtist;
        }

            //---------------REQUÊTE SQL GÉNÉRÉE--------------
        //SELECT alias, description, image
        //FROM artiste
        //WHERE id = 13

        return $this->render('public/artist/show_artist.html.twig', [
            'artist' => $artist
        ]);
    }

    #[Route('/artists/search', 'search_artists', methods: ['GET'])]
    public function searchArtists(Request $request, ArtisteRepository $artisteRepository){

        $search = $request->query->get('search');

        //dd($search);

        $artist = $artisteRepository ->findBySearchInTitle($search);

        //dd($artist);

            //---------------REQUÊTE SQL GÉNÉRÉE--------------
        //SELECT alias, image
        //FROM artiste
        //WHERE alias LIKE:search

        return $this->render('public/artist/search_artists.html.twig', [
            'artists' => $artist,
            'search' => $search
        ]);
    }


}