<?php

namespace App\Repository;
use App\Entity\User;
use App\Entity\Loan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Loan>
 */
class LoanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Loan::class);
    }
    public function findByItemOwner(User $owner): array
    {
        return $this->createQueryBuilder('l')
            ->join('l.item', 'i')
            ->where('i.idUser = :owner')
            ->setParameter('owner', $owner)
            ->orderBy('l.start', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findByBorrower(User $borrower): array
    {
        return $this->createQueryBuilder('l')
            ->where('l.idUser = :borrower')
            ->setParameter('borrower', $borrower)
            ->orderBy('l.start', 'DESC')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Loan[] Returns an array of Loan objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Loan
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
