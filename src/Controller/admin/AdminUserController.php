<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\UsersType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{

    #[Route('/admin/logout', name: 'logout', methods: ['GET'])]
    public function logout()
    {

        return $this->redirectToRoute('login');

        //route utilisée par symfony dans le security.yaml
        //c'est normal qu'elle soit vide

    }

    #[Route('/admin/users/list', name: 'admin_list_users', methods: ['GET'])]
    public function listAdmins(UserRepository $userRepository)
    {


        $admins = $userRepository->findAll();

        return $this->render('admin/user/list_users.html.twig', [
            'admins' => $admins,
        ]);
    }

    //je crée une route associée a ma foncton, elle définit une URL spécifique a cette fonction
    #[Route('/admin/users/create', name: 'admin_create_user', methods: ['GET', 'POST'])]
    //je créé la fonction de création d'utilisateur avec en paramètre la class Request, EntityManager, UserpasswordHasherInterface
    public function createUser(Request $request,
                               EntityManagerInterface $entityManager,
                               UserPasswordHasherInterface $userPasswordHasher) {

        //je créé un instance de la classe User
        $user = new User();

        //j'utilise la méthode "createForm" pour récupérer un formulaire créér en terminal de commande avec la commande "make:form"
        $userForm = $this->createForm(UsersType::class, $user);
        //j'utilise la méthode handleRequest pour récupérer la requête HTTP  de mon formulaire
        $userForm->handleRequest($request);

        //si le fomulaire est soumis et valide
        if ($userForm->isSubmitted() && $userForm->isValid()) {

            //je récupère le mdp entré lors de la création d'utilisateur
            $password = $userForm->get('password')->getData();

            //je hash le mdp grâce a la class UserPasswordInterface et a la méthode "hashPassword"
            $hashedPassword = $userPasswordHasher->hashPassword($user, $password);

            //je récupère le mdp hashé pour l'envoyer en BDD
            $user->setPassword($hashedPassword);

            //je pré-sauvegarde et exécute la création de l'user en BDD
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User créé !!');
        }

        //je créé une vue pour ce formulaire afin que celle ci soit lu dans mon fichier twig
        $userFormView = $userForm->createView();

        return $this->render('admin/user/create_user.html.twig', [
            'userFormView' => $userFormView,

        ]);
    }

    #[Route('admin/users/{id}/delete', name: 'admin_delete_user', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function deleteUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager){


        $user= $userRepository->find($id);
        $authenticatedUser = $this->getUser();

        if($id === $authenticatedUser->getId()){
            $this->addFlash('error', "Vous ne pouvez pas supprimé l'utilisateur connecté");

            return $this->redirectToRoute('admin_list_users');
        }



        $this->addFlash('success', 'Utilisateur bien supprimé');

        return $this->redirectToRoute('admin_list_users',[
            'authenticatedUser' => $authenticatedUser,
            'user' => $user,
        ]);

    }

    #[Route('/admin/users/{id}/update', 'admin_update_user', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function updateUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager,
                               UserPasswordHasherInterface $userPasswordHasher, Request $request){


        $user = $userRepository->find($id);

        //dd($user);

        $adminUserForm = $this->createForm(UsersType::class, $user);

        $adminUserForm->handleRequest($request);

        if ($adminUserForm->isSubmitted() && $adminUserForm->isValid()) {

            $clearNewpassword = $adminUserForm->get('password')->getData();

            if($clearNewpassword){
                $hashedPassword = $userPasswordHasher->hashPassword($user, $clearNewpassword);

                $user->setPassword($hashedPassword);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User créé !!');

            //return $this->redirectToRoute('admin_create_user');

        }

        $adminUserFormView = $adminUserForm->createView();

        return $this->render('admin/user/update_user.html.twig', [
            'user' => $user,
            'adminUserFormView' => $adminUserFormView,
        ]);

    }
}