<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Transaction;
use App\Entity\TransactionStatus;
use App\Entity\TransactionType;
use App\Message\BalanceUpdater;
use App\Repository\TransactionRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class BalanceUpdaterHandler
{
    public function __construct(
        public readonly TransactionRepository $transactionRepository,
        public readonly WalletRepository $walletRepository,
        public readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(BalanceUpdater $balanceUpdater)
    {
        $walletId = $balanceUpdater->getWalletId();

        $wallet = $this->walletRepository->find($walletId);

        $transactions = $this->transactionRepository->findPendingTransactions($walletId);

        $balance = $wallet->getBalance();

        foreach ($transactions as $transaction) {
            /**
             * @var Transaction $transaction
             */
            if ($transaction->getType() === TransactionType::DEBIT) {
                $balance += $transaction->getAmount();
                $transaction->setStatus(TransactionStatus::APPROVED);
            } else {
                $walletBalance = $balance;
                $balance -= $transaction->getAmount();
                $transaction->setStatus(TransactionStatus::APPROVED);
                if ($balance < 0) {
                    $balance = $walletBalance;
                    $transaction->setStatus(TransactionStatus::ERROR);
                }
            }
        }

        $wallet->setBalance($balance);
        $wallet->setIsOccupied(false);

        $this->entityManager->flush();

    }
}