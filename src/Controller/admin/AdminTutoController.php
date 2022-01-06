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
     * je crée une page avec un id qui porte le nom "admin_tuto" et j'ajoute un requirements pour que id
     * devienne un integer
     * @Route("/admin/tuto/{id}", name="admin_tuto", requirements={"id"="\d+"})
     */
    public function showTuto($id, TutoRepository $tutoRepository)
    {



        $tuto = $tutoRepository->find($id);

//je cree une variable tuto qui renvoi a twing la partie de tableau comportant l'id via la methode render
        return $this->render("admin/tuto.html.twig", ["tuto" => $tuto]);

    }


    /**
     * je crée une page qui porte le nom "admin_tutos"
     * @Route("/admin/tutos", name="admin_tutos")
     */
    public function showTutos(TutoRepository $tutoRepository)
    {
        $tutos = $tutoRepository->findAll();

        //je renvoi a twing le tableau via la methode render
        return $this->render("admin/tutos.html.twig", ["tutos" => $tutos]);
    }


    /**
     * je crée une page qui porte le nom "admin_book_create"
     * @Route("/admin/tuto/create", name="admin_tuto_create")
     */
//    je créé une methode qui utilise les classes Request, SluggerInterface et EntityManagerInterface
    public function createTuto(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        //je crée une instance de mon entity Tuto dans ma variable $tuto
        $tuto = new Tuto();
        // j'utilise la methode creatForm de la classe AbstractController pour que symfony créé un formulaire
        // par rapport à $tuto
        $form = $this->createForm(TutoType::class, $tuto);

        // avec la methode handleRequest j'associe le formulaire à $request
        $form->handleRequest($request);

        //  avec la methode isSubmitted je verifie si le formulaire a été soumis et avec la methode isValid verifie sa validité
        if ($form->isSubmitted() && $form->isValid()) {
            // gestion de l'upload d'image
            // je récupére le fichier uploadé
            $tutoFile = $form->get('file_name')->getData();

            if ($tutoFile) {
                // je récupére le nom du fichier uploadé
                $originalFilename = pathinfo($tutoFile->getClientOriginalName(), PATHINFO_FILENAME);

                // je renomme le fichier avec un nom unique
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $tutoFile->guessExtension();


                // je déplace le fichier dans le dossier publique
                $tutoFile->move(
                    $this->getParameter('tuto_directory'),
                    $newFilename
                );

                // j'enregistre le nom du fichier dans la colonne Filename
                $tuto->setFileName($newFilename);
            }
            // cette classe permet de préparer sa sauvegarde en bdd
            $entityManager->persist($tuto);

            // cette classe permet de génèrer et éxecuter la requête SQL
            $entityManager->flush();
            $this->addFlash('success', "le tuto a bien été enregistré!");
        }

        // je renvoie le formulaire créé mis en forme via la methode render dans le twig
        return $this->render("admin/tuto_create.html.twig", [
            'tutoForm' => $form->createView()
        ]);
    }

    /**
     * je crée une page avec un id qui porte le nom "admin_tuto_update"
     * @Route("/admin/tuto/update/{id}", name="admin_tuto_update")
     */
    //  je créé une methode qui fait appel TutoRepository, Request et EntityManagerInterface
    public function tutoUpdate($id, Request $request, TutoRepository $tutoRepository, EntityManagerInterface $entityManager)
    {
        // je mets dans une variable le contenu d'un tuto avec l id de recuperé dans l'url via la methode
        // find de la classe TutoRepository
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

        // je renvoie le formulaire créé via la methode render dans le twig
        return $this->render("admin/tuto_update.html.twig", [
            'tutoForm' => $form->createView()
        ]);

    }

    /**
     * je créé une route /admin/tuto/delete/ qui attend un id et porte le nom admin_tuto_delete
     *@Route("/admin/tuto/delete/{id}", name="admin_tuto_delete")
     */
    // je créé une methode avec en parametre l'id, la classe TutoRepository instanciée dans la variable
    // $tutoRepository et la classe EntityManagerInterface qui est instanciée dans la variable $entityManager
    public function tutoDelete($id, TutoRepository $tutoRepository, EntityManagerInterface $entityManager)
    {
        // je mets dans la variable $tuto le resultat du tuto portant l'id que l on aura recupéré dans l'url
        // en utilisant la methode find de la classe TutoRepository
        $tuto = $tutoRepository->find($id);
        // j'utilise la methode remove de la classe EntityManagerInterface pour preparer la suppression
        $entityManager->remove($tuto);
        // j'utilise la methode flush de la classe EntityManagerInterface pour appliquer la suppression
        $entityManager->flush();
        $this->addFlash('success', "le tuto a bien été supprimé!");
        //je redirige sur la route admin_tutos apres avoir supprimé
        return $this->redirectToRoute('admin_tutos');
    }

}