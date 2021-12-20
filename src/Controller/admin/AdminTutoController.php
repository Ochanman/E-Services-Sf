<?php

namespace App\Controller\admin;

use App\Entity\Tuto;
use App\Form\TutoType;
use App\Repository\TutoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminTutoController extends AbstractController
{

    /**
     * je crée une page book avec un id qui porte le nom "book" et j'ajoute un requirements pour que id
     * devienne un integer
     * @Route("/admin/tuto/{id}", name="admin_tuto", requirements={"id"="\d+"})
     */
    public function showTuto($id, TutoRepository $tutoRepository)
    {



        $tuto = $tutoRepository->find($id);

//je cree une variable article qui renvoi a twing la partie de tableau comportant l'id via la methode render
        return $this->render("admin/tuto.html.twig", ["tuto" => $tuto]);

    }


    /**
     * je crée une page books qui porte le nom "books"
     * @Route("/admin/tutos", name="admin_tutos")
     */
    public function showTutos(TutoRepository $tutoRepository)
    {
        $tutos = $tutoRepository->findAll();

        //je renvoi a twing le tableau via la methode render
        return $this->render("admin/tutos.html.twig", ["tutos" => $tutos]);
    }


    /**
     * je crée une page /admin/book/create qui porte le nom "admin_book_create"
     * @Route("/admin/tuto/create", name="admin_tuto_create")
     */
//    je créé une methode qui utilise les classes Request et EntityManagerInterface
    public function createTuto(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        //je crée une instance de mon entity Book dans ma variable $book
        $tuto = new Tuto();
        // j'utilise la methode creatForm de la classe AbstractController pour que symfony créé un formulaire
        // par rapport à $Book
        $form = $this->createForm(TutoType::class, $tuto);

        // avec la methode handleRequest j'associe le formulaire à $request
        $form->handleRequest($request);

        //  avec la methode isSubmitted je verifie si le formulaire a été soumis et avec la methode isValid verifie sa validité
        if ($form->isSubmitted() && $form->isValid()) {
            // gestion de l'upload d'image
            // 1) récupérer le fichier uploadé
            $tutoFile = $form->get('file_name')->getData();

            if ($tutoFile) {
                // 2) récupérer le nom du fichier uploadé
                $originalFilename = pathinfo($tutoFile->getClientOriginalName(), PATHINFO_FILENAME);

                // 3) renommer le fichier avec un nom unique
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $tutoFile->guessExtension();


                // 4) déplacer le fichier dans le dossier publique
                $tutoFile->move(
                    $this->getParameter('tuto_directory'),
                    $newFilename
                );

                // 5) enregistrer le nom du fichier dans la colonne coverFilename
                $tuto->setFileName($newFilename);
            }
            // cette classe permet de préparer sa sauvegarde en bdd
            $entityManager->persist($tuto);

            // cette classe permet de génèrer et éxecuter la requête SQL
            $entityManager->flush();
            $this->addFlash('success', "le tuto a bien été enregistré!");
        }

        // je renvoie le formulaire créé mis en forme via la methode render sur la page admin/book_create.html.twig
        return $this->render("admin/tuto_create.html.twig", [
            'tutoForm' => $form->createView()
        ]);
    }

    /**
     * je crée une page update avec un id qui porte le nom "book_update"
     * @Route("/admin/tuto/update/{id}", name="admin_tuto_update")
     */
    //  je créé une methose qui fait appel BookRepository et EntityManagerInterface
    public function tutoUpdate($id, Request $request, TutoRepository $tutoRepository, EntityManagerInterface $entityManager)
    {
        // je mets dans une variable le contenu d'un book avec l id de recuperé dans l'url via la methode
        // find de la classe $bookRepository
        $tuto = $tutoRepository->find($id);
        $form = $this->createForm(TutoType::class, $tuto);

        // avec la methode handleRequest j'associe le formulaire à $request
        $form->handleRequest($request);

        //  avec la methode isSubmitted je verifie si le formulaire a été soumis et avec la methode isValid verifie sa validité
        if ($form->isSubmitted() && $form->isValid()) {

            // cette classe permet de préparer sa sauvegarde en bdd
            $entityManager->persist($tuto);

            // cette classe permet de génèrer et éxecuter la requête SQL
            $entityManager->flush();
            $this->addFlash('success', "le tuto a bien été modifié!");
        }

        // je renvoie le formulaire créé mis en forme via la methode render sur la page admin/book_create.html.twig
        return $this->render("admin/tuto_update.html.twig", [
            'tutoForm' => $form->createView()
        ]);

    }



}