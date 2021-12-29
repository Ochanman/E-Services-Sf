<?php

namespace App\Controller\admin;

use App\Repository\ProductRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminProductController extends AbstractController
{

    /**
     * je crée une page book avec un id qui porte le nom "book" et j'ajoute un requirements pour que id
     * devienne un integer
     * @Route("/admin/product/{id}", name="admin_product", requirements={"id"="\d+"})
     */
    public function showProduct($id, ProductRepository $productRepository)
    {



        $product = $productRepository->find($id);

//je cree une variable article qui renvoi a twing la partie de tableau comportant l'id via la methode render
        return $this->render("admin/admin.product.html.twig", ["product" => $product]);

    }


    /**
     * je crée une page racine qui porte le nom "home"
     * @Route("/admin/products", name="admin_products")
     */
    public function Showproducts(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        //je renvoi a twing le tableau via la methode render
        return $this->render("admin/admin.products.html.twig", ["products" => $products]);
    }

    /**
     * @Route("/admin/search", name="admin_search_products")
     */
    // je créé une methode searchBooks utilisant la classe BookRepository et Request
    public function searchProducts(ProductRepository $productRepository, Request $request)
    {
        // je recupére le contenu de l'input de la barre recherche "q" et le mets dans la variable $word
        $word = $request->query->get('q');

        // je fais la requete SQL dans la BDD via la methode searchByTitle de la classe BookRepository
        $products = $productRepository->searchByNumber($word);

        // je retuorne le resultat dans la page admin/books_search.html.twig
        return $this->render('admin/admin.products_search.html.twig', [
            'products' => $products
        ]);

    }


}