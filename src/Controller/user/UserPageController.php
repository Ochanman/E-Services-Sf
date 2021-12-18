<?php

namespace App\Controller\user;
use App\Entity\Repair;
use App\Entity\Product;
use App\Form\DeclareType;
use App\Repository\ProductRepository;
use App\Repository\RepairRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserPageController extends AbstractController
{
    /**
     *@Route("/declare", name="user_declare")
     */

    public function product(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager, ProductRepository $ProductRepository)
    {

        $product = new product();
        $form = $this->createForm(DeclareType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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

                // 5) enregistrer le nom du fichier dans la colonne coverFilename
                $product->setWarranty($newFilename);
            }




                // cette classe permet de préparer sa sauvegarde en bdd
            $entityManager->persist($product);

            // cette classe permet de génèrer et éxecuter la requête SQL
            $entityManager->flush();
            $this->addFlash('success', "Votre demande a bien été prise en compte!");
        }

        // je renvoie le formulaire créé mis en forme via la methode render sur la page admin/book_create.html.twig
        return $this->render("user/declare_create.html.twig", [
            'declareForm' => $form->createView()
        ]);


    }

}