<?php

namespace App\Controller\user;

use App\Entity\Product;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserInfoController extends AbstractController
{
    /**
     *@Route("/user/create_info", name="user_create_info")
     */

    public function createUser(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
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



}