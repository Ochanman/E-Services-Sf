<?php

namespace App\Controller\admin;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPageController extends AbstractController
{
    /**
     * je crée une page racine qui porte le nom "home"
     * @Route("/admin", name="admin_home")
     */
    public function home()
    {


        //je renvoi a twing le tableau via la methode render
        return $this->render("admin/admin.home.html.twig");

    }
}