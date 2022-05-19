<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\TransactionData;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManager;

class TransactionService
{
    public function __construct(
        public readonly EntityManager $entityManager,
        public readonly TransactionRepository $repository
    ) {
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function saveTransaction(TransactionData $transactionData)
    {
        $entity = $this->repository->createEntity(Transaction::class);
        $entity->setWallet($transactionData->getWallet());
        $entity->setAmount($transactionData->getAmount());
        $entity->setType($transactionData->getType());
        $entity->setReason($transactionData->getReason());
        $entity->setStatus($transactionData->getStatus());

        $this->entityManager->persist();
        $this->entityManager->flush();
    }
}