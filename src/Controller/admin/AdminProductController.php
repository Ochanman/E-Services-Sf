<?php

namespace App\Controller\admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;

use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * je crée une page update avec un id qui porte le nom "book_update"
     * @Route("/admin/product/update/{id}", name="admin_product_update")
     */
    //  je créé une methose qui fait appel BookRepository et EntityManagerInterface
    public function ProductUpdate($id, Request $request, ProductRepository $ProductRepository, EntityManagerInterface $entityManager)
    {
        // je mets dans une variable le contenu d'un book avec l id de recuperé dans l'url via la methode
        // find de la classe $bookRepository
        $product = $ProductRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);

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

        // je renvoie le formulaire créé mis en forme via la methode render sur la page admin/book_create.html.twig
        return $this->render("admin/product_update.html.twig", [
            'statusForm' => $form->createView()
        ]);

    }
}