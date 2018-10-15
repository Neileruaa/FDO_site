<?php

namespace App\Repository;

use App\Entity\Danseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Danseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Danseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Danseur[]    findAll()
 * @method Danseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DanseurRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Danseur::class);
    }

//    /**
//     * @return Danseur[] Returns an array of Danseur objects
//     */
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
    public function findOneBySomeField($value): ?Danseur
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
