<?php

namespace App\Controller\user;



use App\Entity\User;
use App\Form\UserType;


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

    public function createUser(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository)
    {
        //je crée une instance de mon entity User dans ma variable $user
        $user = new User();
        // j'utilise la methode creatForm de la classe AbstractController pour que symfony créé un formulaire
        // par rapport à $user
        $form = $this->createForm(UserType::class, $user);

        // avec la methode handleRequest j'associe le formulaire à $request
        $form->handleRequest($request);

        //  avec la methode isSubmitted je verifie si le formulaire a été soumis et avec la methode isValid verifie sa validité
        if($form->isSubmitted() && $form->isValid())
        {
            $mail = $user->getEmail();
            $email = $userRepository->findOneBy(array('email' => $mail));
            if (is_null($email)) {
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
                $this->addFlash('success', "Votre compte a été créé avec succès !");
            }
            else {
                $this->addFlash('success', "Un compte existe déjà avec cet email!");
            }

        }

        // je renvoie le formulaire créé via la methode render sur la page user/info_create.html.twig
        return $this->render("user/info_create.html.twig", [
            'infoForm' => $form->createView()
        ]);




    }

    /**
     * je crée une page /user/info/update/ avec un id qui porte le nom "user_info_update"
     * @Route("/user/info/update/{id}", name="user_info_update")
     */
    //  je créé une methose qui fait appel UserRepository, Request,UserPasswordHasherInterface et EntityManagerInterface
    public function userUpdate($id, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        // je mets dans une variable le contenu d'un User avec l id de recuperé dans l'url via la methode
        // find de la classe UserRepository
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

        // je renvoie le formulaire créé via la methode render sur la page user/info_update.html.twig
        return $this->render("user/info_update.html.twig", [
            'infoForm' => $form->createView()
        ]);

    }

    /**
     * je crée une page /user/info/track/ avec un id qui porte le nom "user_info_track"
     * @Route("/user/info/track/{id}", name="user_info_track")
     */
    //  je créé une methose qui fait appel UserPasswordHasherInterface, Request et EntityManagerInterface
    public function productTrack($id, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        // je mets dans une variable le contenu du User connecté via son getter
        // je mets dans une variable le contenu des products associé a cet user
            $user = $this->getUser();
            $userProducts = $user -> getProduct();


        // je renvoie les données dans un tableau via la methode render sur la page user/user.track.html.twig
        return $this->render("user/user.track.html.twig",
            ["products" => $userProducts,
            "user" => $user]

        );


    }


}