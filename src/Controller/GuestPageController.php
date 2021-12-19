<?php

namespace App\Controller;
use App\Repository\TutoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
//je fais hériter ma classe GuestPageController de la classe AbstractController de Symfony
class GuestPageController extends AbstractController
{
    /**
     * je crée une page racine qui porte le nom "home"
     * @Route("/", name="home")
     */
    //    en parametre le nom de la classe BookRepository instancié dans $bookRepository
    public function home(TutoRepository $tutoRepository)
    {
        //resultat de la requete SQL dans $tuto
        $tutos = $tutoRepository->findAll();

        //réponse HTTP via la méthode render (issue de l'AbstractController)
        return $this->render("guest.home.html.twig", ["tutos" => $tutos]);

    }

    /**
     * je crée une page racine qui porte le nom "home"
     * @Route("/tutos", name="tutos")
     */
    public function showTutos(TutoRepository $tutoRepository)
    {
        $tutos = $tutoRepository->findAll();

        //je renvoi a twing le tableau via la methode render
        return $this->render("guest.tuto.html.twig", ["tutos" => $tutos]);


    }





}