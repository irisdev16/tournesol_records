<?php

namespace App\Controller\admin;

use App\Entity\Style;
use App\Form\AdminStyleType;
use App\Repository\ArtisteRepository;
use App\Repository\StyleRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminStyleController extends AbstractController
{


    #[Route('/admin/style/create', 'admin_style_create', methods: ['GET', 'POST'])]
    public function createStyle(Request $request,EntityManagerInterface $entityManager ){

        $style = new Style();

        //dd($style);

        $adminStyleForm = $this->createForm(AdminStyleType::class, $style);

        $adminStyleForm->handleRequest($request);

        if($adminStyleForm->isSubmitted() && $adminStyleForm->isValid()){

            //dd($adminStyleForm->getData());

            $entityManager->persist($style);
            $entityManager->flush();

                //---------------REQUÊTE SQL GÉNÉRÉE--------------
            //INSERT INTO style (name, created_at, updated_at)
            //VALUES ('nom du style', 'date de création', 'date de modification')

            $this->addFlash('success', 'Style bien créé');

            return $this->redirectToRoute('admin_list_styles');
        }

        $adminStyleFormView = $adminStyleForm->createView();

        return $this->render('admin/style/create_style.html.twig', [
            'adminStyleFormView' => $adminStyleFormView,
        ]);
    }

    #[Route('/admin/styles/list', 'admin_list_styles', methods: ['GET'])]
    public function listStyles(StyleRepository $styleRepository){

        $styles = $styleRepository->findAll();

        //dd($styles);

            //---------------REQUÊTE SQL GÉNÉRÉE--------------

        //SELECT *
        //FROM style

        return $this->render('admin/style/list_styles.html.twig', [
            'styles' => $styles,
        ]);
    }

    #[Route('/admin/style/{id}/show', 'admin_show_style', methods: ['GET'] )]
    public function showStyle(int $id, StyleRepository $styleRepository){

        $style = $styleRepository->find($id);

        //dd($style);

            //---------------REQUÊTE SQL GÉNÉRÉE--------------
        //SELECT *
        //FROM style
        //WHERE id = 3

        return $this->render('admin/style/show_style.html.twig', [
            'style' => $style,
        ]);
    }

    #[Route('admin/style/{id}/delete', 'admin_delete_style', requirements: ['id' => '\d+'] , methods: ['GET'])]
    public function deleteArtist(int $id, StyleRepository $styleRepository, EntityManagerInterface $entityManager){

        $style = $styleRepository->find($id);

        //dd($style);

        $entityManager->remove($style);
        $entityManager->flush();

            //---------------REQUÊTE SQL GÉNÉRÉE--------------
        //DELETE FROM style
        //WHERE id = 4
        $this->addFlash('success', "Le style a bien été supprimé");

        return $this->redirectToRoute('admin_list_styles');
    }

    #[Route('/admin/style/{id}/update', 'admin_update_style', requirements: ['id' => '\d+'] , methods: ['GET', 'POST'])]
    public function updateStyle(int $id, StyleRepository $styleRepository, Request $request, EntityManagerInterface
    $entityManager){

        $style = $styleRepository->find($id);

        //dd($style);

        $adminStyleForm = $this->createForm(AdminStyleType::class, $style);

        $adminStyleForm->handleRequest($request);

        if($adminStyleForm->isSubmitted() && $adminStyleForm->isValid()){

            //dd($adminStyleForm->getData());

            $style->setUpdatedAt(new \DateTimeImmutable('now'));
            $entityManager->persist($style);
            $entityManager->flush();

                //---------------REQUÊTE SQL GÉNÉRÉE--------------
            //UPDATE style
            //SET created_at = 'nouvelle date de création'
            //WHERE id = 8

            $this->addFlash('success', 'Style modifié');
        }

        $adminStyleFormView = $adminStyleForm->createView();

        return $this->render('admin/style/update_style.html.twig', [
            'adminStyleFormView' => $adminStyleFormView,
            'style' => $style,
        ]);
    }
}