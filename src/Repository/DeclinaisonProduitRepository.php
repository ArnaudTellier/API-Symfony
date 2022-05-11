<?php

namespace App\Repository;

use App\Entity\DeclinaisonProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeclinaisonProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeclinaisonProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeclinaisonProduit[]    findAll()
 * @method DeclinaisonProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeclinaisonProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeclinaisonProduit::class);
    }

    // /**
    //  * @return DeclinaisonProduit[] Returns an array of DeclinaisonProduit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeclinaisonProduit
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
