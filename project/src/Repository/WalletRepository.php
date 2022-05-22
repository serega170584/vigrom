<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TransactionStatus;
use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wallet>
 *
 * @method Wallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wallet[]    findAll()
 * @method Wallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletRepository extends ServiceEntityRepository
{
    private TransactionRepository $transactionRepository;

    public function __construct(
        ManagerRegistry $registry,
        TransactionRepository $transactionRepository
    )
    {
        parent::__construct($registry, Wallet::class);
        $this->transactionRepository = $transactionRepository;
    }

    public function add(Wallet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Wallet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Wallet[] Returns an array of Wallet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Wallet
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findPendingWallet(int $walletId): Wallet
    {
        $wallets = $this->createQueryBuilder('w')
            ->innerJoin('w.transactions', 't')
            ->andWhere('w.id = :id')->setParameter('id', $walletId)
            ->where('t.status = :status')->setParameter('status', TransactionStatus::NEW)
            ->getQuery()
            ->toIterable();
        return current($wallets);
    }

}
