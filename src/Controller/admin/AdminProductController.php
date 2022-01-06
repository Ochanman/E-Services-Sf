<?php

namespace App\Controller\admin;



use App\Form\ProductAdminType;
use App\Repository\ProductRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminProductController extends AbstractController
{

    /**
     * je crée une page avec un id qui porte le nom "admin_product" et j'ajoute un requirements pour que id
     * devienne un integer
     * @Route("/admin/product/{id}", name="admin_product", requirements={"id"="\d+"})
     */

    public function ProductUpdate($id, Request $request, ProductRepository $ProductRepository, EntityManagerInterface $entityManager)
    {
        // je mets dans une variable le contenu d'un product avec l id recuperé dans l'url via la methode
        // find de la classe ProductRepository

        $product = $ProductRepository->find($id);

        $form = $this->createForm(ProductAdminType::class, $product);

        // avec la methode handleRequest j'associe le formulaire à $request
        $form->handleRequest($request);

        //  avec la methode isSubmitted je verifie si le formulaire a été soumis et avec la methode isValid verifie sa validité
        if ($form->isSubmitted() && $form->isValid()) {

            // cette classe permet de préparer sa sauvegarde en bdd
            $entityManager->persist($product);

            // cette classe permet de génèrer et éxecuter la requête SQL
            $entityManager->flush();
            $this->addFlash('success', "le dossier a bien été modifié!");
        }

        // je renvoie le formulaire créé mis en forme via la methode render dans le twig
        return $this->render("admin/admin.product.html.twig", [
            'statusForm' => $form->createView(),
            "product" => $product
        ]);

    }


    /**
     * je crée une page racine qui porte le nom "admin_products"
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
    // je créé une methode searchProducts utilisant la classe ProductRepository et Request
    public function searchProducts(ProductRepository $productRepository, Request $request)
    {
        // je recupére le contenu de l'input de la barre recherche "q" et le mets dans la variable $word
        $word = $request->query->get('q');

        // je fais la requete SQL dans la BDD via la methode searchByNumber de la classe ProductRepository
        $products = $productRepository->searchByNumber($word);

        // je retourne le resultat dans le twig
        return $this->render('admin/admin.products_search.html.twig', [
            'products' => $products
        ]);

    }


}