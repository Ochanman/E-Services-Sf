<?php

namespace App\Controller;
use App\Repository\TutoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
class GuestPageController extends AbstractController
{
    /**
     * je crée une page racine qui porte le nom "home"
     * @Route("/", name="home")
     */
    public function home(TutoRepository $tutoRepository)
    {

        $tutos = $tutoRepository->findAll();

        //je renvoi a twing le tableau via la methode render
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
/**
* @Route("test", name="test")
*/
public function test()
{
    return $this->render("guest.test.html.twig");
}

}