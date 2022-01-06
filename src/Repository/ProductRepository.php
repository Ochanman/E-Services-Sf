<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // je créé la methode searchByNumber pour faire la requete sql en prenant la variable $word qui est
    // le resultat de l'input "q"
    public function searchByNumber($word) {


        // je demande à Doctrine de créer une requête SQL
        // qui fait une requête SELECT sur la table product
        // à condition que le number du product
        // contiennent le contenu de $word (à un endroit ou à un autre, grâce à LIKE %xxxx%)
        $queryBuilder = $this->createQueryBuilder('product');

        // la requete sql s'execute et je recupère le resultat dans la variable $query
        $query = $queryBuilder->select('product')
            ->where('product.number LIKE :word')
            ->setParameter('word', '%'.$word.'%')
            ->getQuery();

        // je retourne le resultat via la methode getResult()
        return $query->getResult();

    }
}
