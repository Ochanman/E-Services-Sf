<?php

namespace App\Controller\user;

use App\Entity\Product;

use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserDeclareController extends AbstractController
{
    /**
     *@Route("/user/declare/{id}", name="user_declare")
     */

    public function declare($id, Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager, ProductRepository $ProductRepository)
    {
        $user = $this->getUser();
        $declare = new product();
        $form = $this->createForm(ProductType::class, $declare);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $declare->setUser($user);

            // gestion de l'upload d'image
            // 1) récupérer le fichier uploadé
            $warrantyFile = $form->get('warranty')->getData();
            if ($warrantyFile) {
                // 2) récupérer le nom du fichier uploadé
                $originalFilename = pathinfo($warrantyFile->getClientOriginalName(), PATHINFO_FILENAME);

                // 3) renommer le fichier avec un nom unique

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$warrantyFile->guessExtension();

                // 4) déplacer le fichier dans le dossier publique
                $warrantyFile->move(
                    $this->getParameter('warranty_directory'),
                    $newFilename
                );

                // 5) enregistrer le nom du fichier dans la colonne Warranty
                $declare->setWarranty($newFilename);
            }

            // faire une requête dans la table des pannes, pour récupérer la dernière panne
            $lastfailure = $ProductRepository->findOneBy([], ['id' => 'DESC']);
            // si y'en a pas, tu créé le numero ($number) de la première panne à la main str_pad (000001)
            if (!$lastfailure) {

                $number =str_pad(1, 6, "0", STR_PAD_LEFT);

                // sinon tu récupère le numéro de la dernière panne, tu l'incrémente de 1 : $number
            } else {
                $number = (int)$lastfailure->getNumber();
                $number += 1;
                $number =str_pad($number, 6, "0", STR_PAD_LEFT);
            }


            // tu ajoutes à ta panne le numéro
            $declare->setNumber($number);


                // cette classe permet de préparer sa sauvegarde en bdd
            $entityManager->persist($declare);

            // cette classe permet de génèrer et éxecuter la requête SQL
            $entityManager->flush();
            $this->addFlash('success', "Votre demande a bien été prise en compte!");
        }

        // je renvoie le formulaire créé via la methode render sur la page user/declare_create.html.twig
        return $this->render("user/declare_create.html.twig", [
            'declareForm' => $form->createView()
        ]);


    }



}