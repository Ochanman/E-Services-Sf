<?php

namespace App\Controller\admin;

use App\Repository\ProductRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPageController extends AbstractController
{
    /**
     * je crée une page racine qui porte le nom "admin_home"
     * @Route("/admin", name="admin_home")
     */
    public function Showproducts(ProductRepository $productRepository)
    {
        $products = $productRepository->findBy([], ['id' => 'DESC'],10);

        //je renvoi a twing le tableau via la methode render
        return $this->render("admin/admin.home.html.twig", ["products" => $products]);
    }
}