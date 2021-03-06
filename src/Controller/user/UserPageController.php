<?php

namespace App\Controller\user;
use App\Repository\TutoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserPageController extends AbstractController
{
    /**
     * je crée une page qui porte le nom "user_home"
     * @Route("/user", name="user_home")
     */
    public function home(TutoRepository $tutoRepository)
    {

        $tutos = $tutoRepository->findAll();

        //je renvoi a twing le tableau via la methode render
        return $this->render("user/user.home.html.twig", ["tutos" => $tutos]);

    }

    /**
     * je crée une page /user/tutos qui porte le nom "user_tutos"
     * @Route("/user/tutos", name="user_tutos")
     */
    public function showTutos(TutoRepository $tutoRepository)
    {
        $tutos = $tutoRepository->findAll();

        //je renvoi a twing le tableau via la methode render
        return $this->render("user/user.tuto.html.twig", ["tutos" => $tutos]);


    }





}