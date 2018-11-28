<?php

namespace App\Repository;

use App\Entity\Dancer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Dancer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dancer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dancer[]    findAll()
 * @method Dancer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DancerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Dancer::class);
    }

	public function findAllByAuthorized() {
		return $this->createQueryBuilder('d')
			->orderBy('d.isAuthorized', 'ASC')
			->getQuery()
			->getArrayResult()
			;
	}


//    /**
//     * @return Dancer[] Returns an array of Dancer objects
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
    public function findOneBySomeField($value): ?Dancer
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
