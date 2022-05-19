<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Transaction;
use App\Entity\TransactionStatus;
use App\Entity\TransactionType;
use App\Entity\Wallet;
use App\Traits\CreateEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    use CreateEntityTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function add(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Transaction[] Returns an array of Transaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Transaction
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getDebit(Wallet $wallet): int
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount)')
            ->andWhere('t.wallet=:wallet')->setParameter('wallet', $wallet)
            ->andWhere('t.status=:status')->setParameter('status', TransactionStatus::APPROVED)
            ->andWhere('t.type=:type')->setParameter('type', TransactionType::DEBIT)
            ->orderBy('t.id')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getCredit(Wallet $wallet): int
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount)')
            ->andWhere('t.wallet=:wallet')->setParameter('wallet', $wallet)
            ->andWhere('t.status=:status')->setParameter('status', TransactionStatus::APPROVED)
            ->andWhere('t.type=:type')->setParameter('type', TransactionType::CREDIT)
            ->orderBy('t.id')
            ->getQuery()
            ->getSingleScalarResult();
    }

}
