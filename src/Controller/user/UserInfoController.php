<?php

namespace App\Controller\user;

use App\Entity\Product;

use App\Entity\User;
use App\Form\UserType;

use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserInfoController extends AbstractController
{
    /**
     *@Route("/create_info", name="user_create_info")
     */

    public function createUser(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher)
    {
        //je crée une instance de mon entity Book dans ma variable $book
        $user = new User();
        // j'utilise la methode creatForm de la classe AbstractController pour que symfony créé un formulaire
        // par rapport à $Book
        $form = $this->createForm(UserType::class, $user);

        // avec la methode handleRequest j'associe le formulaire à $request
        $form->handleRequest($request);

        //  avec la methode isSubmitted je verifie si le formulaire a été soumis et avec la methode isValid verifie sa validité
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(["ROLE_USER"]);
        //  je vais chercher les informations de password et plus precisement les données
            $plaintextPassword = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            // cette classe permet de préparer sa sauvegarde en bdd
            $entityManager->persist($user);

            // cette classe permet de génèrer et éxecuter la requête SQL
            $entityManager->flush();
            $this->addFlash('success', "Vos informations ont bien été enregistré!");
        }

        // je renvoie le formulaire créé mis en forme via la methode render sur la page admin/book_create.html.twig
        return $this->render("user/info_create.html.twig", [
            'infoForm' => $form->createView()
        ]);




    }

    /**
     * je crée une page update avec un id qui porte le nom "book_update"
     * @Route("/user/info/update/{id}", name="user_info_update")
     */
    //  je créé une methose qui fait appel BookRepository et EntityManagerInterface
    public function userUpdate($id, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        // je mets dans une variable le contenu d'un book avec l id de recuperé dans l'url via la methode
        // find de la classe $bookRepository
        $user = $userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);

        // avec la methode handleRequest j'associe le formulaire à $request
        $form->handleRequest($request);

        //  avec la methode isSubmitted je verifie si le formulaire a été soumis et avec la methode isValid verifie sa validité
        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            // cette classe permet de préparer sa sauvegarde en bdd
            $entityManager->persist($user);

            // cette classe permet de génèrer et éxecuter la requête SQL
            $entityManager->flush();
            $this->addFlash('success', "Vos compte a bien été modifié!");
        }

        // je renvoie le formulaire créé mis en forme via la methode render sur la page admin/book_create.html.twig
        return $this->render("user/info_update.html.twig", [
            'infoForm' => $form->createView()
        ]);

    }

    /**
     * je crée une page update avec un id qui porte le nom "book_update"
     * @Route("/user/info/track/{id}", name="user_info_track")
     */
    //  je créé une methose qui fait appel BookRepository et EntityManagerInterface
    public function productTrack($id, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        // je mets dans une variable le contenu d'un book avec l id de recuperé dans l'url via la methode
        // find de la classe $bookRepository




            $user = $this->getUser();
            $userProducts = $user -> getProduct();


        // je renvoie le formulaire créé mis en forme via la methode render sur la page admin/book_create.html.twig
        return $this->render("user/user.track.html.twig",
            ["products" => $userProducts,
            "user" => $user]
        );

    }


}