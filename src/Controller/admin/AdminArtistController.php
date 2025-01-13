<?php

namespace App\Controller\admin;

use App\Entity\Artiste;
use App\Form\AdminArtistType;
use App\Repository\ArtisteRepository;
use App\service\UniqueFilenameGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminArtistController extends AbstractController
{

    #[Route('/admin/artist/create', name: 'admin_artist_create', methods: ['GET', 'POST'])]
    public function createArtist(Request $request, UniqueFilenameGenerator $uniqueFilenameGenerator,
                                 ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager){

        $artist = new Artiste();

        $adminArtistForm = $this ->createForm(AdminArtistType::class, $artist);

        $adminArtistForm->handleRequest($request);

        if($adminArtistForm->isSubmitted() && $adminArtistForm->isValid()){

            $artistImage = $adminArtistForm->get('image')->getData();

            // Si aucune image n'a été soumise, ajouter un message d'erreur
            if (!$artistImage) {
                $this->addFlash('error', "Veuillez télécharger une image pour l'artiste.");
                return $this->redirectToRoute('admin_artist_create'); // Redirige vers la même page pour afficher le message

            }

            if($artistImage){

                $imageOriginalName = $artistImage->getClientOriginalName();
                $imageExtension = $artistImage->guessExtension();

                $imageNewFilename = $uniqueFilenameGenerator->generateUniqueFilename($imageOriginalName, $imageExtension);

                // je récupère grâce à la classe ParameterBag, le chemin vers la racine du projet
                $rootDir = $parameterBag->get('kernel.project_dir');

                // je génère le chemin vers le dossier uploads (dans le dossier public)
                $uploadsDir = $rootDir . '/public/assets/uploads';

                // je déplace mon image dans le dossier uploads, en lui donnant le nom unique
                $artistImage->move($uploadsDir, $imageNewFilename);

                // je stocke dans l'entité le nouveau nom de l'image
                $artist->setImage($imageNewFilename);
            }

            $entityManager->persist($artist);
            $entityManager->flush();

            $this->addFlash('success', 'Artiste bien créé');

            return $this->redirectToRoute('admin_list_artists');
        }

        $adminArtistFormView = $adminArtistForm->createView();

        return $this->render('admin/artist/create_artist.html.twig', [
            'adminArtistFormView' => $adminArtistFormView,
        ]);
    }

    #[Route('/admin/artists/list', 'admin_list_artists', methods: ['GET'])]
    public function listArtists(ArtisteRepository $artistRepository){

        $artists = $artistRepository->findAll();

        return $this->render('admin/artist/list_artists.html.twig', [
            'artists' => $artists,
        ]);
    }

    #[Route('/admin/artist/{id}/show', 'admin_show_artist', methods: ['GET'])]
    public function showArtist(int $id, ArtisteRepository $artistRepository){

        $artist = $artistRepository->find($id);

        return $this->render('admin/artist/show_artist.html.twig', [
            'artist' => $artist,
        ]);
    }

    //ma classe AdminArtistController hérite de AbstractController, class symfony
    //je créé une route associée a la méthode ci dessous,c'est cette url qui s'affichera dans le navigateur
    //requirements est écrit sous forme d'expression régulière : id spécifique qui doit être un chiffre
    #[Route('admin/artist/{id}/delete', 'admin_delete_artist', requirements: ['id' => '\d+'])]
    //je créé la fonction de suppression d'artiste avec en paramètre l'id de l'artiste, le repository, et l'entity Manager
    public function deleteArtist(int $id, ArtisteRepository $artisteRepository, EntityManagerInterface $entityManager){



        // j'interroge le repository, donc la BDD pour récupérer l'artiste avec l'id qui lui ai associé
        $artist = $artisteRepository->find($id);

        //j'utilise l'entity Manager pour supprimer l'artiste (remove)
        $entityManager->remove($artist);
        //j'envoie ça en BDD
        $entityManager->flush();

        //j'utilise la méthode addFlash issu de la classe AbstractController pour afficher un message de validation
        $this->addFlash('success', "L'artiste a bien été supprimé");

        //méthode redirectTotRoute également issu d'AbstractController pour rediriger vers la liste d'artistes
        return $this->redirectToRoute('admin_list_artists');
    }


    #[Route ('/admin/artist/{id}/update', 'admin_update_artist', requirements: ['id' => '\d+'] , methods: ['GET', 'POST'])]
    public function updateArtist(int $id, UniqueFilenameGenerator $uniqueFilenameGenerator, ArtisteRepository
    $artisteRepository, Request $request, EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag){

        $artist = $artisteRepository->find($id);

        $adminArtistForm = $this ->createForm(AdminArtistType::class, $artist);

        $adminArtistForm->handleRequest($request);

        if($adminArtistForm->isSubmitted() && $adminArtistForm->isValid()){

            $artistImage = $adminArtistForm->get('image')->getData();

            if($artistImage){

                $imageOriginalName = $artistImage->getClientOriginalName();
                $imageExtension = $artistImage->guessExtension();

                $imageNewFilename = $uniqueFilenameGenerator->generateUniqueFilename($imageOriginalName, $imageExtension);

                $rootDir = $parameterBag->get('kernel.project_dir');
                $uploadsDir = $rootDir . '/public/assets/uploads';
                $artistImage->move($uploadsDir, $imageNewFilename);

                $artist->setImage($imageNewFilename);
                $artist->setUpdatedAt(new \DateTimeImmutable('now'));
            }

            $entityManager->persist($artist);
            $entityManager->flush();

            $this->addFlash('success', 'Artiste modifié');
        }

        $adminArtistFormView = $adminArtistForm->createView();

        return $this->render('admin/artist/update_artist.html.twig', [
            'adminArtistFormView' => $adminArtistFormView,
            'artist' => $artist,
        ]);




    }

}