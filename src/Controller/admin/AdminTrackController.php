<?php

namespace App\Controller\admin;

use App\Entity\Track;
use App\Form\AdminStyleType;
use App\Form\AdminTrackType;
use App\Repository\StyleRepository;
use App\Repository\TrackRepository;
use App\service\UniqueFilenameGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminTrackController extends AbstractController
{

    #[Route('/admin/track/create', 'admin_track_create', methods: ['GET', 'POST'])]
    public function createTrack(Request $request, UniqueFilenameGenerator $uniqueFilenameGenerator,
                                ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager){

        $track = new Track();

        //dd($track);

        $adminTrackForm = $this ->createForm(AdminTrackType::class, $track);

        $adminTrackForm->handleRequest($request);

        if($adminTrackForm->isSubmitted() && $adminTrackForm->isValid()){

            //dd($adminTrackForm->getData());

            $trackImage = $adminTrackForm->get('image')->getData();

            // Si aucune image n'a été soumise, ajouter un message d'erreur
            if (!$trackImage) {
                $this->addFlash('error', "Veuillez télécharger une image pour l'artiste.");
                return $this->redirectToRoute('admin_track_create'); // Redirige vers la même page pour afficher le message

            }

            if($trackImage){

                $imageOriginalName = $trackImage->getClientOriginalName();
                $imageExtension = $trackImage->guessExtension();

                $imageNewFilename = $uniqueFilenameGenerator->generateUniqueFilename($imageOriginalName, $imageExtension);

                //dd($imageNewFilename);

                // je récupère grâce à la classe ParameterBag, le chemin vers la racine du projet
                $rootDir = $parameterBag->get('kernel.project_dir');

                // je génère le chemin vers le dossier uploads (dans le dossier public)
                $uploadsDir = $rootDir . '/public/assets/uploads';

                // je déplace mon image dans le dossier uploads, en lui donnant
                // le nom unique
                $trackImage->move($uploadsDir, $imageNewFilename);

                // je stocke dans l'entité le nouveau nom de l'image
                $track->setImage($imageNewFilename);

                //dd($track);
            }

            $entityManager->persist($track);
            $entityManager->flush();

                //---------------REQUÊTE SQL GÉNÉRÉE--------------
            //INSERT INTO track (title, description, release_at, style, artiste_id, created_at, updated_at,
            // spotify_link, apple_music_link, youtube_link, image)
            //VALUES ('titre de la track', 'description de la track', ETC ...

            $this->addFlash('success', 'Track créée avec succès');

            return $this->redirectToRoute('admin_list_tracks');
        }

        return $this->render('admin/track/create_track.html.twig', [
            'adminTrackForm' => $adminTrackForm->createView(),
        ]);
    }

    #[Route('/admin/tracks/list', 'admin_list_tracks', methods: ['GET'])]
    public function listTracks(TrackRepository $trackRepository){

        $tracks = $trackRepository->findAll();

        //dd($tracks);

            //---------------REQUÊTE SQL GÉNÉRÉE--------------
        //SELECT id, title, description, image, artiste_id, released_at, created_at,updated_at
        //FROM track

        return $this->render('admin/track/list_tracks.html.twig', [
            'tracks' => $tracks
        ]);
    }

    #[Route('/admin/track/{id}/show', 'admin_show_track', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function showTrack(int $id, TrackRepository $trackRepository) {

        $track = $trackRepository->find($id);

        //dd($track);

            //---------------REQUÊTE SQL GÉNÉRÉE--------------
        //SELECT *
        //FROM track
        //WHERE id = 5

        return $this->render('admin/track/show_track.html.twig', [
            'track' => $track
        ]);
    }

    #[Route('/admin/track/{id}/delete', 'admin_delete_track', requirements: ['id' => '\d+'], methods: ['GET','POST'])]
    public function deleteTrack(int $id, TrackRepository $trackRepository, EntityManagerInterface $entityManager){

        $track = $trackRepository->find($id);

        //dd($track);

        $entityManager->remove($track);
        $entityManager->flush();

            //---------------REQUÊTE SQL GÉNÉRÉE--------------
        //DELETE FROM track
        //WHERE id= 32

        $this->addFlash('success', 'La track a bien été supprimée');

        return $this->redirectToRoute('admin_list_tracks');
    }


    #[Route('/admin/track/{id}/update', 'admin_update_track', requirements: ['id' => '\d+'] , methods: ['GET', 'POST'])]
    public function updateStyle(int $id,
                                ParameterBagInterface $parameterBag,
                                UniqueFilenameGenerator $uniqueFilenameGenerator,
                                TrackRepository $trackRepository,
                                Request $request,
                                EntityManagerInterface $entityManager){

        $track = $trackRepository->find($id);

        //dd($track);

        $adminTrackForm = $this->createForm(AdminTrackType::class, $track);

        $adminTrackForm->handleRequest($request);

        if($adminTrackForm->isSubmitted() && $adminTrackForm->isValid()){

            //dd($adminTrackForm->getData());

            $trackImage = $adminTrackForm->get('image')->getData();

            if($trackImage){

                $imageOriginalName = $trackImage->getClientOriginalName();
                $imageExtension = $trackImage->guessExtension();

                $imageNewFilename = $uniqueFilenameGenerator->generateUniqueFilename($imageOriginalName, $imageExtension);

                $rootDir = $parameterBag->get('kernel.project_dir');
                $uploadsDir = $rootDir . '/public/assets/uploads';
                $trackImage->move($uploadsDir, $imageNewFilename);

                $track->setImage($imageNewFilename);
                $track->setUpdatedAt(new \DateTimeImmutable('now'));

                //dd($track);
            }

            $track->setUpdatedAt(new \DateTimeImmutable('now'));
            $entityManager->persist($track);
            $entityManager->flush();

                //---------------REQUÊTE SQL GÉNÉRÉE--------------
            //UPDATE track
            //SET title = 'nouveau titre', description = 'nouvelle description', ETC...
            //WHERE id = 12

            $this->addFlash('success', 'Track modifiée');
        }

        $adminTrackFormView = $adminTrackForm->createView();

        return $this->render('admin/track/update_track.html.twig', [
            'adminTrackFormView' => $adminTrackFormView,
            'track' => $track,
        ]);
    }
}